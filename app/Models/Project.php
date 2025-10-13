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
		'allocated_amount',
		'available_amount',
		'remarks',
		'ministry_address',
		'office_order_number',
		'office_order_date',
		'created_by',
		'updated_by',
		'deleted_by',
	];

	protected $casts = [
		'allocated_amount' => 'decimal:2',
		'available_amount' => 'decimal:2',
		'office_order_date' => 'date',
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
	 * Scope a query to only include active projects (current economic year).
	 */
	public function scopeActive($query)
	{
		return $query->whereHas('economicYear', function($q) {
			$q->where('is_current', true);
		});
	}

	/**
	 * Scope a query to only include completed projects.
	 */
	public function scopeCompleted($query)
	{
		return $query->whereHas('economicYear', function($q) {
			$q->where('end_date', '<', now());
		});
	}

	/**
	 * Scope a query to only include upcoming projects.
	 */
	public function scopeUpcoming($query)
	{
		return $query->whereHas('economicYear', function($q) {
			$q->where('start_date', '>', now());
		});
	}

	/**
	 * Get formatted allocated amount with appropriate unit.
	 */
	public function getFormattedAllocatedAmountAttribute(): string
	{
		$unit = $this->reliefType?->unit_bn ?? $this->reliefType?->unit ?? '';
		$amount = number_format((float)$this->allocated_amount, 2);
		
		// For Taka/Cash, show with currency symbol
		if (in_array($unit, ['টাকা', 'Taka'])) {
			return '৳' . $amount;
		}
		
		// For other units, show with unit name
		return $amount . ' ' . $unit;
	}

	/**
	 * Get formatted available amount with appropriate unit.
	 */
	public function getFormattedAvailableAmountAttribute(): string
	{
		$unit = $this->reliefType?->unit_bn ?? $this->reliefType?->unit ?? '';
		$amount = number_format((float)$this->available_amount, 2);
		
		// For Taka/Cash, show with currency symbol
		if (in_array($unit, ['টাকা', 'Taka'])) {
			return '৳' . $amount;
		}
		
		// For other units, show with unit name
		return $amount . ' ' . $unit;
	}

	/**
	 * Get formatted used amount with appropriate unit.
	 */
	public function getFormattedUsedAmountAttribute(): string
	{
		$usedAmount = $this->allocated_amount - $this->available_amount;
		$unit = $this->reliefType?->unit_bn ?? $this->reliefType?->unit ?? '';
		$amount = number_format((float)$usedAmount, 2);
		
		// For Taka/Cash, show with currency symbol
		if (in_array($unit, ['টাকা', 'Taka'])) {
			return '৳' . $amount;
		}
		
		// For other units, show with unit name
		return $amount . ' ' . $unit;
	}

	/**
	 * Check if this project is active (belongs to current economic year).
	 */
	public function getIsActiveAttribute(): bool
	{
		return $this->economicYear?->is_current ?? false;
	}

	/**
	 * Get the status of this project.
	 */
	public function getStatusAttribute(): string
	{
		if ($this->economicYear?->is_current) {
			return 'Active';
		}
		
		if ($this->economicYear && now()->isAfter($this->economicYear->end_date)) {
			return 'Completed';
		}
		
		return 'Upcoming';
	}
}