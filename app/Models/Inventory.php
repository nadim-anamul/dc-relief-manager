<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'relief_item_id',
        'project_id',
        'current_stock',
        'reserved_stock',
        'total_received',
        'total_distributed',
        'unit_price',
        'last_updated',
        'notes',
    ];

    protected $casts = [
        'current_stock' => 'decimal:3',
        'reserved_stock' => 'decimal:3',
        'total_received' => 'decimal:3',
        'total_distributed' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'last_updated' => 'date',
    ];

    /**
     * Get the relief item.
     */
    public function reliefItem(): BelongsTo
    {
        return $this->belongsTo(ReliefItem::class);
    }

    /**
     * Get the project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get available stock (current_stock - reserved_stock).
     */
    public function getAvailableStockAttribute()
    {
        return $this->current_stock - $this->reserved_stock;
    }

    /**
     * Get formatted current stock display.
     */
    public function getFormattedCurrentStockAttribute()
    {
        return number_format($this->current_stock, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted available stock display.
     */
    public function getFormattedAvailableStockAttribute()
    {
        return number_format($this->available_stock, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted reserved stock display.
     */
    public function getFormattedReservedStockAttribute()
    {
        return number_format($this->reserved_stock, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted total received display.
     */
    public function getFormattedTotalReceivedAttribute()
    {
        return number_format($this->total_received, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted total distributed display.
     */
    public function getFormattedTotalDistributedAttribute()
    {
        return number_format($this->total_distributed, 3) . ' ' . $this->reliefItem->formatted_unit;
    }

    /**
     * Get formatted unit price display.
     */
    public function getFormattedUnitPriceAttribute()
    {
        if ($this->unit_price) {
            return '৳' . number_format($this->unit_price, 2) . ' per ' . $this->reliefItem->formatted_unit;
        }
        return 'N/A';
    }

    /**
     * Update inventory when items are received.
     */
    public function receiveStock($quantity, $unitPrice = null)
    {
        $this->current_stock += $quantity;
        $this->total_received += $quantity;
        
        if ($unitPrice) {
            $this->unit_price = $unitPrice;
        }
        
        $this->last_updated = now();
        $this->save();
    }

    /**
     * Reserve stock for approved applications.
     */
    public function reserveStock($quantity)
    {
        if ($this->available_stock >= $quantity) {
            $this->reserved_stock += $quantity;
            $this->last_updated = now();
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Release reserved stock.
     */
    public function releaseReservedStock($quantity)
    {
        $this->reserved_stock = max(0, $this->reserved_stock - $quantity);
        $this->last_updated = now();
        $this->save();
    }

    /**
     * Distribute stock (move from reserved to distributed).
     */
    public function distributeStock($quantity)
    {
        if ($this->reserved_stock >= $quantity) {
            $this->reserved_stock -= $quantity;
            $this->total_distributed += $quantity;
            $this->last_updated = now();
            $this->save();
            return true;
        }
        return false;
    }
}
