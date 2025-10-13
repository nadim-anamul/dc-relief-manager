<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ReliefApplication extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	protected $fillable = [
		'organization_name',
		'organization_type_id',
		'date',
		'zilla_id',
		'upazila_id',
		'union_id',
		'ward_id',
		'subject',
		'relief_type_id',
		'project_id',
		'applicant_name',
		'applicant_designation',
		'applicant_phone',
		'applicant_address',
		'organization_address',
		'amount_requested',
		'approved_amount',
		'details',
		'admin_remarks',
		'status',
		'application_file',
		'approved_by',
		'approved_at',
		'rejected_by',
		'rejected_at',
		'created_by',
		'updated_by',
		'deleted_by',
	];

	protected $casts = [
		'date' => 'date',
		'amount_requested' => 'decimal:2',
		'approved_amount' => 'decimal:2',
		'approved_at' => 'datetime',
		'rejected_at' => 'datetime',
	];

	/**
	 * Get the organization type for this application.
	 */
	public function organizationType(): BelongsTo
	{
		return $this->belongsTo(OrganizationType::class);
	}

	/**
	 * Get the zilla for this application.
	 */
	public function zilla(): BelongsTo
	{
		return $this->belongsTo(Zilla::class);
	}

	/**
	 * Get the upazila for this application.
	 */
	public function upazila(): BelongsTo
	{
		return $this->belongsTo(Upazila::class);
	}

	/**
	 * Get the union for this application.
	 */
	public function union(): BelongsTo
	{
		return $this->belongsTo(Union::class);
	}

	/**
	 * Get the ward for this application.
	 */
	public function ward(): BelongsTo
	{
		return $this->belongsTo(Ward::class);
	}

	/**
	 * Get the relief type for this application.
	 */
	public function reliefType(): BelongsTo
	{
		return $this->belongsTo(ReliefType::class);
	}

	/**
	 * Get the project for this application.
	 */
	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class);
	}

	/**
	 * Get the user who approved this application.
	 */
	public function approvedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'approved_by');
	}

	/**
	 * Get the user who rejected this application.
	 */
	public function rejectedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'rejected_by');
	}

	/**
	 * Get the user who created this application.
	 */
	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * Get the user who last updated this application.
	 */
	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	/**
	 * Get the user who deleted this application.
	 */
	public function deletedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'deleted_by');
	}

	/**
	 * Get the relief application items for this application.
	 */
	public function reliefApplicationItems(): HasMany
	{
		return $this->hasMany(ReliefApplicationItem::class);
	}

	/**
	 * Scope to filter by status.
	 */
	public function scopeByStatus($query, $status)
	{
		return $query->where('status', $status);
	}

	/**
	 * Scope to filter by relief type.
	 */
	public function scopeByReliefType($query, $reliefTypeId)
	{
		return $query->where('relief_type_id', $reliefTypeId);
	}

	/**
	 * Scope to filter by organization type.
	 */
	public function scopeByOrganizationType($query, $organizationTypeId)
	{
		return $query->where('organization_type_id', $organizationTypeId);
	}

	/**
	 * Scope to filter by zilla.
	 */
	public function scopeByZilla($query, $zillaId)
	{
		return $query->where('zilla_id', $zillaId);
	}

	/**
	 * Get formatted amount requested.
	 */
	public function getFormattedAmountAttribute(): string
	{
		$unit = $this->reliefType?->unit_bn ?? $this->reliefType?->unit ?? '';
		$amount = number_format((float)$this->amount_requested, 2);
		
		// For Taka/Cash, show with currency symbol
		if (in_array($unit, ['টাকা', 'Taka'])) {
			return '৳' . $amount;
		}
		
		// For other units, show with unit name
		return $amount . ' ' . $unit;
	}

	/**
	 * Get status badge class.
	 */
	public function getStatusBadgeClassAttribute(): string
	{
		return match($this->status) {
			'pending' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
			'approved' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
			'rejected' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
			default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
		};
	}

	/**
	 * Get status display name.
	 */
	public function getStatusDisplayAttribute(): string
	{
		return match($this->status) {
			'pending' => __('Pending'),
			'approved' => __('Approved'),
			'rejected' => __('Rejected'),
			default => __('Unknown'),
		};
	}

	/**
	 * Get file URL if exists.
	 */
	public function getFileUrlAttribute(): ?string
	{
		return $this->application_file ? asset('storage/' . $this->application_file) : null;
	}

	/**
	 * Get file name from path.
	 */
	public function getFileNameAttribute(): ?string
	{
		return $this->application_file ? basename($this->application_file) : null;
	}

	/**
	 * Get formatted approved amount.
	 */
	public function getFormattedApprovedAmountAttribute(): string
	{
		if (!$this->approved_amount) {
			return '-';
		}

		$unit = $this->project?->reliefType?->unit_bn ?? $this->project?->reliefType?->unit ?? '';
		$amount = number_format((float)$this->approved_amount, 2);
		
		// For Taka/Cash, show with currency symbol
		if (in_array($unit, ['টাকা', 'Taka'])) {
			return '৳' . $amount;
		}
		
		// For other units, show with unit name
		return $amount . ' ' . $unit;
	}

	/**
	 * Approve the application and deduct budget from project.
	 */
	public function approve($approvedAmount, $projectId, $adminRemarks = null, $approvedBy = null): bool
	{
		return DB::transaction(function () use ($approvedAmount, $projectId, $adminRemarks, $approvedBy) {
			// Check if project exists and has sufficient budget
			$project = Project::find($projectId);
			if (!$project) {
				throw new \Exception('Project not found');
			}

			if ($project->available_amount < $approvedAmount) {
				throw new \Exception('Insufficient project budget');
			}

			// Update application
			$this->update([
				'status' => 'approved',
				'approved_amount' => $approvedAmount,
				'project_id' => $projectId,
				'admin_remarks' => $adminRemarks,
				'approved_by' => $approvedBy,
				'approved_at' => now(),
				'rejected_by' => null,
				'rejected_at' => null,
			]);

			// Deduct budget from project
			$project->decrement('available_amount', $approvedAmount);

			return true;
		});
	}

	/**
	 * Reject the application.
	 */
	public function reject($adminRemarks = null, $rejectedBy = null): bool
	{
		$this->update([
			'status' => 'rejected',
			'admin_remarks' => $adminRemarks,
			'rejected_by' => $rejectedBy,
			'rejected_at' => now(),
			'approved_by' => null,
			'approved_at' => null,
		]);

		return true;
	}

	/**
	 * Check if application can be approved.
	 */
	public function canBeApproved(): bool
	{
		return $this->status === 'pending';
	}

	/**
	 * Check if application can be rejected.
	 */
	public function canBeRejected(): bool
	{
		return $this->status === 'pending';
	}

	/**
	 * Get available projects for this relief type.
	 */
	public function getAvailableProjectsAttribute()
	{
		return Project::where('relief_type_id', $this->relief_type_id)
			->whereHas('economicYear', function($query) {
				$query->where('start_date', '<=', now())
					->where('end_date', '>=', now())
					->where('is_current', true);
			})
			->where('available_amount', '>', 0)
			->orderBy('name')
			->get();
	}
}