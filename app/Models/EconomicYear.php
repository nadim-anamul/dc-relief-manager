<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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

    public function getNameDisplayAttribute(): string
    {
        $localizedName = app()->isLocale('bn') ? ($this->name_bn ?: $this->name) : ($this->name ?: $this->name_bn);
        if ($localizedName) {
            return (string) $localizedName;
        }
        $start = $this->start_date ? Carbon::parse($this->start_date)->format('Y') : null;
        $end = $this->end_date ? Carbon::parse($this->end_date)->format('Y') : null;
        if (function_exists('locale_is_bn') && locale_is_bn()) {
            return trim((function_exists('bn_number') ? bn_number($start) : $start) . ' - ' . (function_exists('bn_number') ? bn_number($end) : $end));
        }
        return trim(($start ?: '') . ' - ' . ($end ?: ''));
    }

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
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        return $start->diffInDays($end);
	}

	/**
	 * Get the duration of the economic year in months.
	 */
    public function getDurationInMonthsAttribute(): int
	{
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        return $start->diffInMonths($end);
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