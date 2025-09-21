<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReliefType extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'name_bn',
		'description',
		'description_bn',
		'unit',
		'unit_bn',
		'color_code',
		'is_active',
		'sort_order',
	];

	protected $casts = [
		'is_active' => 'boolean',
		'sort_order' => 'integer',
	];

	/**
	 * Get the relief requests for this relief type.
	 * Note: ReliefRequest model will be created later
	 */
	// public function reliefRequests(): HasMany
	// {
	// 	return $this->hasMany(ReliefRequest::class);
	// }

	/**
	 * Scope to get only active relief types.
	 */
	public function scopeActive($query)
	{
		return $query->where('is_active', true);
	}

	/**
	 * Scope to order by sort order.
	 */
	public function scopeOrdered($query)
	{
		return $query->orderBy('sort_order')->orderBy('name');
	}

	/**
	 * Get the relief type with its color styling.
	 */
	public function getColorStyleAttribute(): string
	{
		return $this->color_code ? "background-color: {$this->color_code};" : '';
	}

	/**
	 * Get the display name (Bengali if available, otherwise English).
	 */
	public function getDisplayNameAttribute(): string
	{
		return $this->name_bn ?: $this->name;
	}

	/**
	 * Get the display unit (Bengali if available, otherwise English).
	 */
	public function getDisplayUnitAttribute(): string
	{
		return $this->unit_bn ?: $this->unit;
	}
}