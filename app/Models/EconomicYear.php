<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EconomicYear extends Model
{
	use HasFactory, Auditable;

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
        // Count months inclusively (e.g., Jul 1 to Jun 30 = 12 months)
        return $start->diffInMonths($end) + 1;
    }

	/**
	 * Check if a given date falls within this economic year.
	 */
	public function containsDate($date): bool
	{
		$date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
		return $date->between($this->start_date, $this->end_date);
	}

	/**
	 * Automatically determine and set the current economic year based on today's date.
	 * 
	 * @return EconomicYear|null The current economic year, or null if none found
	 */
	public static function updateCurrentYear(): ?EconomicYear
	{
		$today = Carbon::today();
		
		// Find the economic year that contains today's date
		$currentYear = static::where('is_active', true)
			->where('start_date', '<=', $today)
			->where('end_date', '>=', $today)
			->orderBy('start_date', 'desc')
			->first();
		
		if (!$currentYear) {
			// Fallback: find the most recent active year that hasn't ended yet
			$currentYear = static::where('is_active', true)
				->where('end_date', '>=', $today)
				->orderBy('start_date', 'asc')
				->first();
		}
		
		if ($currentYear) {
			// Check if this year is already set as current
			$existingCurrent = static::where('is_current', true)->first();
			
			if (!$existingCurrent || $existingCurrent->id !== $currentYear->id) {
				// Update all years to not current
				static::query()->update(['is_current' => false]);
				
				// Set the found year as current
				$currentYear->update(['is_current' => true]);
				
				// Log the change if logging is enabled
                if (config('economic-year.log_changes', true)) {
                    Log::info("Updated current economic year to: {$currentYear->name} (ID: {$currentYear->id})");
                }
			}
			
			return $currentYear;
		}
		
		// Log warning if no suitable economic year found
        if (config('economic-year.log_changes', true)) {
            Log::warning('No suitable economic year found for current date: ' . $today->format('Y-m-d'));
        }
		
		return null;
	}

	/**
	 * Find the economic year that contains the given date.
	 * 
	 * @param string|Carbon $date
	 * @return EconomicYear|null
	 */
	public static function findByDate($date): ?EconomicYear
	{
		$date = is_string($date) ? Carbon::parse($date) : $date;
		
		return static::where('is_active', true)
			->where('start_date', '<=', $date)
			->where('end_date', '>=', $date)
			->orderBy('start_date', 'desc')
			->first();
	}

	/**
	 * Get the current economic year based on the is_current flag.
	 * 
	 * @return EconomicYear|null
	 */
	public static function getCurrentByDate(): ?EconomicYear
	{
		return static::where('is_current', true)->first();
	}

	/**
	 * Get the current economic year, automatically updating if needed.
	 * This method ensures you always get the correct current year.
	 * 
	 * @return EconomicYear|null
	 */
	public static function getCurrent(): ?EconomicYear
	{
		$current = static::getCurrentByDate();
		
		// If no current year is set, try to update automatically
		if (!$current) {
			$current = static::updateCurrentYear();
		}
		
		return $current;
	}
}