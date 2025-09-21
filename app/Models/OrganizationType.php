<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationType extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'description',
	];

	/**
	 * Get the organizations for this organization type.
	 * Note: Organization model will be created later
	 */
	// public function organizations(): HasMany
	// {
	// 	return $this->hasMany(Organization::class);
	// }

	/**
	 * Scope to order organization types by name.
	 */
	public function scopeOrdered($query)
	{
		return $query->orderBy('name');
	}

	/**
	 * Get the display name for the organization type.
	 */
	public function getDisplayNameAttribute(): string
	{
		return $this->name;
	}
}