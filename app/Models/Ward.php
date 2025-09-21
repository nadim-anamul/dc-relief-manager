<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ward extends Model
{
	use HasFactory;

	protected $fillable = [
		'union_id',
		'name',
		'name_bn',
		'code',
		'is_active',
	];

	protected $casts = [
		'is_active' => 'boolean',
	];

	/**
	 * Get the union that owns the ward.
	 */
	public function union(): BelongsTo
	{
		return $this->belongsTo(Union::class);
	}

	/**
	 * Get the upazila through union.
	 */
	public function upazila(): BelongsTo
	{
		return $this->belongsTo(Upazila::class, 'upazila_id', 'id')
			->through('union');
	}

	/**
	 * Get the zilla through upazila.
	 */
	public function zilla(): BelongsTo
	{
		return $this->belongsTo(Zilla::class, 'zilla_id', 'id')
			->through('upazila');
	}
}