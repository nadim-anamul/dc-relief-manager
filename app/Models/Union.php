<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Union extends Model
{
	use HasFactory;

	protected $fillable = [
		'upazila_id',
		'name',
		'name_bn',
		'code',
		'is_active',
	];

	protected $casts = [
		'is_active' => 'boolean',
	];

	/**
	 * Get the upazila that owns the union.
	 */
	public function upazila(): BelongsTo
	{
		return $this->belongsTo(Upazila::class);
	}

	/**
	 * Get the zilla through upazila.
	 */
	public function zilla(): BelongsTo
	{
		return $this->belongsTo(Zilla::class, 'zilla_id', 'id')
			->through('upazila');
	}

	/**
	 * Get the wards for the union.
	 */
	public function wards(): HasMany
	{
		return $this->hasMany(Ward::class);
	}
}