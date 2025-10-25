<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReliefType extends Model
{
	use HasFactory, Auditable;

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
	 * Get the projects for this relief type.
	 */
	public function projects(): HasMany
	{
		return $this->hasMany(Project::class);
	}

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
		return app()->isLocale('bn') ? ($this->name_bn ?: $this->name) : ($this->name ?: $this->name_bn);
	}

	/**
	 * Get the display unit (Bengali if available, otherwise English).
	 */
	public function getDisplayUnitAttribute(): string
	{
		return app()->isLocale('bn') ? ($this->unit_bn ?: $this->unit) : ($this->unit ?: $this->unit_bn);
	}
}