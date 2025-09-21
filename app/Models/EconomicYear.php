<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EconomicYear extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'name_bn',
		'start_date',
		'end_date',
		'is_active',
		'is_current',
	];

	protected $casts = [
		'start_date' => 'date',
		'end_date' => 'date',
		'is_active' => 'boolean',
		'is_current' => 'boolean',
	];

	/**
	 * Get the relief requests for this economic year.
	 * Note: ReliefRequest model will be created later
	 */
	// public function reliefRequests(): HasMany
	// {
	// 	return $this->hasMany(ReliefRequest::class);
	// }

	/**
	 * Scope to get only active economic years.
	 */
	public function scopeActive($query)
	{
		return $query->where('is_active', true);
	}

	/**
	 * Scope to get the current economic year.
	 */
	public function scopeCurrent($query)
	{
		return $query->where('is_current', true);
	}

	/**
	 * Get the duration of the economic year in days.
	 */
	public function getDurationInDaysAttribute(): int
	{
		return $this->start_date->diffInDays($this->end_date);
	}

	/**
	 * Get the duration of the economic year in months.
	 */
	public function getDurationInMonthsAttribute(): int
	{
		return $this->start_date->diffInMonths($this->end_date);
	}

	/**
	 * Check if a given date falls within this economic year.
	 */
	public function containsDate($date): bool
	{
		$date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
		return $date->between($this->start_date, $this->end_date);
	}
}