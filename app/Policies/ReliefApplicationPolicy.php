<?php

namespace App\Policies;

use App\Models\ReliefApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReliefApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'relief-applications.view',
            'relief-applications.view-own',
            'relief-applications.approve',
            'relief-applications.reject'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReliefApplication $reliefApplication): bool
    {
        // Super admin can view all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // District admin can view all in their district
        if ($user->isDistrictAdmin()) {
            return true; // For now, district admin can view all
        }

        // Data entry can view all
        if ($user->isDataEntry()) {
            return true;
        }

        // Viewer can view all
        if ($user->isViewer()) {
            return true;
        }

        // Regular users can view their own applications
        return $user->id === $reliefApplication->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission([
            'relief-applications.create',
            'relief-applications.create-own'
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReliefApplication $reliefApplication): bool
    {
        // Super admin can update all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // District admin can update all
        if ($user->isDistrictAdmin()) {
            return true;
        }

        // Data entry can update all
        if ($user->isDataEntry()) {
            return true;
        }

        // Regular users can only update their own pending applications
        return $user->id === $reliefApplication->created_by && $reliefApplication->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReliefApplication $reliefApplication): bool
    {
        // Super admin can delete all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // District admin can delete all
        if ($user->isDistrictAdmin()) {
            return true;
        }

        // Regular users can only delete their own pending applications
        return $user->id === $reliefApplication->created_by && $reliefApplication->status === 'pending';
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, ReliefApplication $reliefApplication): bool
    {
        return $user->hasPermissionTo('relief-applications.approve') && 
               $reliefApplication->status === 'pending';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, ReliefApplication $reliefApplication): bool
    {
        return $user->hasPermissionTo('relief-applications.reject') && 
               $reliefApplication->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReliefApplication $reliefApplication): bool
    {
        return $user->isSuperAdmin() || $user->isDistrictAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReliefApplication $reliefApplication): bool
    {
        return $user->isSuperAdmin();
    }
}