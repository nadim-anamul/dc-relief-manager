<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReliefApplicationItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'relief_application_id',
        'relief_item_id',
        'quantity_requested',
        'quantity_approved',
        'unit_price',
        'total_amount',
        'remarks',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:3',
        'quantity_approved' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the relief application that owns this item.
     */
    public function reliefApplication(): BelongsTo
    {
        return $this->belongsTo(ReliefApplication::class);
    }

    /**
     * Get the relief item.
     */
    public function reliefItem(): BelongsTo
    {
        return $this->belongsTo(ReliefItem::class);
    }

    /**
     * Calculate total amount based on quantity and unit price.
     */
    public function calculateTotalAmount()
    {
        if ($this->quantity_approved && $this->unit_price) {
            $this->total_amount = $this->quantity_approved * $this->unit_price;
        }
        return $this->total_amount;
    }

    /**
     * Get formatted quantity display.
     */
    public function getFormattedQuantityRequestedAttribute()
    {
        return number_format($this->quantity_requested, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted approved quantity display.
     */
    public function getFormattedQuantityApprovedAttribute()
    {
        if ($this->quantity_approved) {
            return number_format($this->quantity_approved, 3) . ' ' . $this->reliefItem->formatted_unit;
        }
        return 'Not approved';
    }

    /**
     * Get formatted total amount display.
     */
    public function getFormattedTotalAmountAttribute()
    {
        if ($this->total_amount) {
            return '৳' . number_format($this->total_amount, 2);
        }
        return 'N/A';
    }
}
