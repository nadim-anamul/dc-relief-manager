<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
	/**
	 * Boot the auditable trait.
	 */
	protected static function bootAuditable()
	{
		// Log when a model is created
		static::created(function (Model $model) {
			$model->logAuditEvent('created', null, $model->getAttributes());
		});

		// Log when a model is updated
		static::updated(function (Model $model) {
			$model->logAuditEvent('updated', $model->getOriginal(), $model->getChanges());
		});

		// Log when a model is deleted (soft delete)
		static::deleted(function (Model $model) {
			if ($model->isSoftDelete()) {
				$model->logAuditEvent('deleted', $model->getAttributes(), null);
			}
		});

		// Log when a model is restored
		static::restored(function (Model $model) {
			$model->logAuditEvent('restored', null, $model->getAttributes());
		});
	}

	/**
	 * Log an audit event.
	 */
	public function logAuditEvent(string $event, ?array $oldValues = null, ?array $newValues = null, ?string $remarks = null): void
	{
		$changedFields = null;
		
		if ($oldValues && $newValues) {
			$changedFields = array_keys($newValues);
		}

		AuditLog::create([
			'auditable_type' => get_class($this),
			'auditable_id' => $this->getKey(),
			'event' => $event,
			'old_values' => $oldValues,
			'new_values' => $newValues,
			'changed_fields' => $changedFields,
			'user_id' => Auth::id(),
			'ip_address' => Request::ip(),
			'user_agent' => Request::userAgent(),
			'url' => Request::fullUrl(),
			'remarks' => $remarks,
		]);
	}

	/**
	 * Get audit logs for this model.
	 */
	public function auditLogs()
	{
		return $this->morphMany(AuditLog::class, 'auditable')->orderBy('created_at', 'desc');
	}

	/**
	 * Get the latest audit log.
	 */
	public function latestAuditLog()
	{
		return $this->morphOne(AuditLog::class, 'auditable')->latest();
	}

	/**
	 * Check if this model uses soft deletes.
	 */
	protected function isSoftDelete(): bool
	{
		return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($this));
	}

	/**
	 * Manually log an audit event.
	 */
	public function audit(string $event, ?string $remarks = null): void
	{
		$this->logAuditEvent($event, null, null, $remarks);
	}

	/**
	 * Log a custom audit event with specific values.
	 */
	public function auditWithValues(string $event, ?array $oldValues = null, ?array $newValues = null, ?string $remarks = null): void
	{
		$this->logAuditEvent($event, $oldValues, $newValues, $remarks);
	}
}
