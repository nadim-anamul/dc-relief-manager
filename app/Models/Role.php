<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if the role has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Check if the role has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $permissionCount = $this->permissions()->whereIn('slug', $permissionSlugs)->count();
        return $permissionCount === count($permissionSlugs);
    }

    /**
     * Scope a query to only include active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}