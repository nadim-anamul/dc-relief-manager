<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_type_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the organization type for this user.
     */
    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    /**
     * Get the roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->exists();
    }

    /**
     * Check if the user has all of the given roles.
     */
    public function hasAllRoles(array $roleSlugs): bool
    {
        $roleCount = $this->roles()->whereIn('slug', $roleSlugs)->count();
        return $roleCount === count($roleSlugs);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }

    /**
     * Check if the user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlugs) {
            $query->whereIn('slug', $permissionSlugs);
        })->exists();
    }

    /**
     * Check if the user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $permissionCount = $this->roles()->whereHas('permissions', function ($query) use ($permissionSlugs) {
            $query->whereIn('slug', $permissionSlugs);
        })->count();
        return $permissionCount === count($permissionSlugs);
    }

    /**
     * Get all permissions for the user through their roles.
     */
    public function getAllPermissions()
    {
        return $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('id');
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is district admin.
     */
    public function isDistrictAdmin(): bool
    {
        return $this->hasRole('district-admin');
    }

    /**
     * Check if user is data entry.
     */
    public function isDataEntry(): bool
    {
        return $this->hasRole('data-entry');
    }

    /**
     * Check if user is viewer.
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }
}
