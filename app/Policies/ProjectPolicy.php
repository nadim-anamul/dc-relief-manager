<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'projects.view',
            'projects.view-own',
            'projects.manage'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Super admin can view all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // District admin can view all
        if ($user->isDistrictAdmin()) {
            return true;
        }

        // Data entry can view all
        if ($user->isDataEntry()) {
            return true;
        }

        // Viewer can view all
        if ($user->isViewer()) {
            return true;
        }

        // Regular users can view projects they created
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission([
            'projects.create',
            'projects.manage'
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
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

        // Regular users can only update projects they created
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Super admin can delete all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // District admin can delete all
        if ($user->isDistrictAdmin()) {
            return true;
        }

        // Regular users can only delete projects they created
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->isSuperAdmin() || $user->isDistrictAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->isSuperAdmin();
    }
}