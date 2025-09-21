<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\OrganizationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'organizationType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $organizationTypes = OrganizationType::all();
        
        return view('admin.users.create', compact('roles', 'organizationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^(\+88)?01[3-9]\d{8}$/',
            'password' => 'required|string|min:8|confirmed',
            'organization_type_id' => 'nullable|exists:organization_types,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'is_approved' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'organization_type_id' => $request->organization_type_id,
            'is_approved' => $request->boolean('is_approved', true), // Default to approved for admin-created users
            'email_verified_at' => now(),
        ]);

        // Assign roles
        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'organizationType']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $organizationTypes = OrganizationType::all();
        $user->load('roles');
        
        return view('admin.users.edit', compact('user', 'roles', 'organizationTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|regex:/^(\+88)?01[3-9]\d{8}$/',
            'password' => 'nullable|string|min:8|confirmed',
            'organization_type_id' => 'nullable|exists:organization_types,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'is_approved' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization_type_id' => $request->organization_type_id,
            'is_approved' => $request->boolean('is_approved'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $userData['must_change_password'] = false; // Clear must change password flag
        }

        $user->update($userData);

        // Sync roles
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of super admin users
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete super admin users.');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Approve a user.
     */
    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User approved successfully.');
    }

    /**
     * Reject a user.
     */
    public function reject(User $user)
    {
        $user->update(['is_approved' => false]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User rejected successfully.');
    }

    /**
     * Generate temporary password for user.
     */
    public function generateTempPassword(User $user)
    {
        $tempPassword = $user->generateTempPassword();
        $user->setTempPassword($tempPassword, 24); // 24 hours expiry

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Temporary password generated: {$tempPassword}. This password will expire in 24 hours.");
    }

    /**
     * Clear temporary password for user.
     */
    public function clearTempPassword(User $user)
    {
        $user->clearTempPassword();

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Temporary password cleared successfully.');
    }
}
