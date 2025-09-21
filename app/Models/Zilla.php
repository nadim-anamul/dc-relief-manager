<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zilla extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	protected $fillable = [
		'name',
		'name_bn',
		'code',
		'is_active',
		'created_by',
		'updated_by',
		'deleted_by',
	];

	protected $casts = [
		'is_active' => 'boolean',
	];

	/**
	 * Get the upazilas for the zilla.
	 */
	public function upazilas(): HasMany
	{
		return $this->hasMany(Upazila::class);
	}

	/**
	 * Get all unions through upazilas.
	 */
	public function unions(): HasManyThrough
	{
		return $this->hasManyThrough(Union::class, Upazila::class);
	}

	/**
	 * Get all wards through unions.
	 */
	public function wards(): HasManyThrough
	{
		return $this->hasManyThrough(Ward::class, Union::class, 'upazila_id', 'union_id', 'id', 'id');
	}

	/**
	 * Get the user who created this zilla.
	 */
	public function createdBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * Get the user who last updated this zilla.
	 */
	public function updatedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'updated_by');
	}

	/**
	 * Get the user who deleted this zilla.
	 */
	public function deletedBy(): BelongsTo
	{
		return $this->belongsTo(User::class, 'deleted_by');
	}
}