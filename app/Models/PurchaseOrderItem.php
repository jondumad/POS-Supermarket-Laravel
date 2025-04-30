<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the purchase order that this item belongs to
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * Get the product for this purchase order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Calculate subtotal
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->unit_price;
        $this->save();
        
        return $this;
    }
    
    /**
     * Boot method to set up event listeners
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            if (empty($item->subtotal)) {
                $item->subtotal = $item->quantity * $item->unit_price;
            }
        });
        
        static::updating(function ($item) {
            if ($item->isDirty(['quantity', 'unit_price'])) {
                $item->subtotal = $item->quantity * $item->unit_price;
            }
        });
        
        static::created(function ($item) {
            $item->purchaseOrder->calculateTotal();
        });
        
        static::updated(function ($item) {
            $item->purchaseOrder->calculateTotal();
        });
        
        static::deleted(function ($item) {
            $item->purchaseOrder->calculateTotal();
        });
    }
}

