<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
	use HasFactory;

	protected $fillable = [
		'auditable_type',
		'auditable_id',
		'event',
		'old_values',
		'new_values',
		'changed_fields',
		'user_id',
		'ip_address',
		'user_agent',
		'url',
		'remarks',
	];

	protected $casts = [
		'old_values' => 'array',
		'new_values' => 'array',
		'changed_fields' => 'array',
	];

	/**
	 * Get the user who made the change.
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the auditable model.
	 */
	public function auditable(): MorphTo
	{
		return $this->morphTo();
	}

	/**
	 * Scope to filter by event type.
	 */
	public function scopeByEvent($query, $event)
	{
		return $query->where('event', $event);
	}

	/**
	 * Scope to filter by user.
	 */
	public function scopeByUser($query, $userId)
	{
		return $query->where('user_id', $userId);
	}

	/**
	 * Scope to filter by auditable type.
	 */
	public function scopeByAuditableType($query, $type)
	{
		return $query->where('auditable_type', $type);
	}

	/**
	 * Scope to filter by date range.
	 */
	public function scopeByDateRange($query, $startDate, $endDate)
	{
		return $query->whereBetween('created_at', [$startDate, $endDate]);
	}

	/**
	 * Get the event display name.
	 */
	public function getEventDisplayAttribute(): string
	{
		return match ($this->event) {
			'created' => 'Created',
			'updated' => 'Updated',
			'deleted' => 'Deleted',
			'restored' => 'Restored',
			default => ucfirst($this->event),
		};
	}

	/**
	 * Get the event badge class.
	 */
	public function getEventBadgeClassAttribute(): string
	{
		return match ($this->event) {
			'created' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
			'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
			'deleted' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
			'restored' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
			default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
		};
	}

	/**
	 * Get formatted changed fields.
	 */
	public function getFormattedChangedFieldsAttribute(): string
	{
		if (!$this->changed_fields) {
			return 'No fields changed';
		}

		return implode(', ', $this->changed_fields);
	}

	/**
	 * Get the model name from auditable type.
	 */
	public function getModelNameAttribute(): string
	{
		$parts = explode('\\', $this->auditable_type);
		return end($parts);
	}

	/**
	 * Get formatted old values.
	 */
	public function getFormattedOldValuesAttribute(): string
	{
		if (!$this->old_values) {
			return 'N/A';
		}

		$formatted = [];
		foreach ($this->old_values as $key => $value) {
			$formatted[] = $key . ': ' . (is_array($value) ? json_encode($value) : $value);
		}

		return implode(', ', $formatted);
	}

	/**
	 * Get formatted new values.
	 */
	public function getFormattedNewValuesAttribute(): string
	{
		if (!$this->new_values) {
			return 'N/A';
		}

		$formatted = [];
		foreach ($this->new_values as $key => $value) {
			$formatted[] = $key . ': ' . (is_array($value) ? json_encode($value) : $value);
		}

		return implode(', ', $formatted);
	}
}