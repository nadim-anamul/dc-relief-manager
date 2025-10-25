<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReliefItem extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'name_bn',
        'type',
        'unit',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameDisplayAttribute(): string
    {
        return app()->isLocale('bn') ? ($this->name_bn ?: $this->name) : ($this->name ?: $this->name_bn);
    }

    public function getUnitDisplayAttribute(): string
    {
        return app()->isLocale('bn') ? ($this->unit_bn ?: $this->unit) : ($this->unit ?: $this->unit_bn);
    }

    /**
     * Get the relief application items for this relief item.
     */
    public function reliefApplicationItems(): HasMany
    {
        return $this->hasMany(ReliefApplicationItem::class);
    }

    /**
     * Get the inventories for this relief item.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Scope to get only active relief items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get formatted unit display.
     */
    public function getFormattedUnitAttribute()
    {
        $units = [
            'BDT' => '৳',
            'metric_ton' => 'Metric Ton',
            'kg' => 'Kg',
            'liter' => 'Liter',
            'piece' => 'Piece',
            'box' => 'Box',
        ];

        return $units[$this->unit] ?? $this->unit;
    }

    /**
     * Get formatted type display.
     */
    public function getFormattedTypeAttribute()
    {
        $types = [
            'monetary' => 'Monetary',
            'food' => 'Food',
            'medical' => 'Medical',
            'shelter' => 'Shelter',
            'other' => 'Other',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
