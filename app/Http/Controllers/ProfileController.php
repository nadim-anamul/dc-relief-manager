<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|regex:/^(\+88)?01[3-9]\d{8}$/',
            'organization_type_id' => 'nullable|exists:organization_types,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization_type_id' => $request->organization_type_id,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Clear temporary password if it exists
        $user->clearTempPassword();

        return redirect()->route('profile.edit')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Show password change form (for users with temporary passwords).
     */
    public function showPasswordChangeForm(Request $request)
    {
        $user = $request->user();
        
        if (!$user->mustChangePassword()) {
            return redirect()->route('dashboard');
        }

        return view('profile.change-password', [
            'user' => $user,
        ]);
    }

    /**
     * Change password (for users with temporary passwords).
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        if (!$user->mustChangePassword()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'temp_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Verify temporary password
        if (!$user->hasValidTempPassword() || !Hash::check($request->temp_password, $user->temp_password)) {
            return back()->withErrors(['temp_password' => 'Invalid or expired temporary password.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Clear temporary password
        $user->clearTempPassword();

        return redirect()->route('dashboard')
            ->with('success', 'Password changed successfully.');
    }
}