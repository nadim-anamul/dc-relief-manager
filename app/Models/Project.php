<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	protected $fillable = [
		'name',
		'economic_year_id',
		'relief_type_id',
		'budget',
		'start_date',
		'end_date',
		'remarks',
		'created_by',
		'updated_by',
		'deleted_by',
	];

	protected $casts = [
		'budget' => 'decimal:2',
		'start_date' => 'date',
		'end_date' => 'date',
	];

	/**
	 * Get the economic year for this project.
	 */
	public function economicYear(): BelongsTo
	{
		return $this->belongsTo(EconomicYear::class);
	}

	/**
	 * Get the relief type for this project.
	 */
	public function reliefType(): BelongsTo
	{
		return $this->belongsTo(ReliefType::class);
	}

	/**
	 * Get the user who created this project.
	 */
	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * Get the user who last updated this project.
	 */
	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	/**
	 * Get the user who deleted this project.
	 */
	public function deletedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'deleted_by');
	}

	/**
	 * Get the inventories for this project.
	 */
	public function inventories(): HasMany
	{
		return $this->hasMany(Inventory::class);
	}

	/**
	 * Scope to filter by economic year.
	 */
	public function scopeForEconomicYear($query, $economicYearId)
	{
		return $query->where('economic_year_id', $economicYearId);
	}

	/**
	 * Scope to filter by relief type.
	 */
	public function scopeForReliefType($query, $reliefTypeId)
	{
		return $query->where('relief_type_id', $reliefTypeId);
	}

	/**
	 * Scope to filter by date range.
	 */
	public function scopeInDateRange($query, $startDate, $endDate)
	{
		return $query->whereBetween('start_date', [$startDate, $endDate]);
	}

	/**
	 * Get the duration of the project in days.
	 */
	public function getDurationInDaysAttribute(): int
	{
		return $this->start_date->diffInDays($this->end_date);
	}

	/**
	 * Get the duration of the project in months.
	 */
	public function getDurationInMonthsAttribute(): int
	{
		return $this->start_date->diffInMonths($this->end_date);
	}

	/**
	 * Check if the project is currently active (within date range).
	 */
	public function getIsActiveAttribute(): bool
	{
		$now = now();
		return $now->between($this->start_date, $this->end_date);
	}

	/**
	 * Check if the project is completed (past end date).
	 */
	public function getIsCompletedAttribute(): bool
	{
		return now()->isAfter($this->end_date);
	}

	/**
	 * Check if the project is upcoming (before start date).
	 */
	public function getIsUpcomingAttribute(): bool
	{
		return now()->isBefore($this->start_date);
	}

	/**
	 * Get formatted budget amount.
	 */
	public function getFormattedBudgetAttribute(): string
	{
		return '৳' . number_format($this->budget, 2);
	}
}