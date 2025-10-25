<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^(\+88)?01[3-9]\d{8}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_approved' => false, // Require admin approval
        ]);

        // Send notification to admins about new user registration
        $this->notifyAdminsOfNewUser($user);

        // Send notification to user about pending approval
        $this->notifyUserOfPendingApproval($user);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Notify admins about new user registration.
     */
    private function notifyAdminsOfNewUser(User $user): void
    {
        // Get all super-admin and district-admin users
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super-admin', 'district-admin']);
        })->where('is_approved', true)->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewUserRegistrationNotification($user));
        }
    }

    /**
     * Notify user about pending approval.
     */
    private function notifyUserOfPendingApproval(User $user): void
    {
        $user->notify(new \App\Notifications\UserPendingApprovalNotification());
    }
}
