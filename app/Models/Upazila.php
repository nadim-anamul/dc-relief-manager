<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Upazila extends Model
{
	use HasFactory;

	protected $fillable = [
		'zilla_id',
		'name',
		'name_bn',
		'code',
		'is_active',
	];

	protected $casts = [
		'is_active' => 'boolean',
	];

    public function getNameDisplayAttribute(): string
    {
        return app()->isLocale('bn') ? ($this->name_bn ?: $this->name) : ($this->name ?: $this->name_bn);
    }

	/**
	 * Get the zilla that owns the upazila.
	 */
	public function zilla(): BelongsTo
	{
		return $this->belongsTo(Zilla::class);
	}

	/**
	 * Get the unions for the upazila.
	 */
	public function unions(): HasMany
	{
		return $this->hasMany(Union::class);
	}

	/**
	 * Get all wards through unions.
	 */
	public function wards()
	{
		return $this->hasManyThrough(Ward::class, Union::class);
	}
}