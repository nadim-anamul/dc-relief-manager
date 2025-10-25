<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Super admin bypasses approval check
        if ($user instanceof User && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user is approved
        if ($user instanceof User && !$user->isApproved()) {
            // Logout the user
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', __('Your account is pending admin approval. Please wait for an administrator to approve your account.'));
        }

        return $next($request);
    }
}
