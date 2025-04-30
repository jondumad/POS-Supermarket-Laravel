<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'alert_threshold',
        'location',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'alert_threshold' => 'integer',
    ];

    /**
     * Get the product that this inventory item belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Check if inventory is low on stock
     */
    public function isLowOnStock()
    {
        return $this->quantity <= $this->alert_threshold;
    }
    
    /**
     * Add stock to inventory
     */
    public function addStock(int $quantity)
    {
        $this->quantity += $quantity;
        $this->save();
        
        return $this;
    }
    
    /**
     * Remove stock from inventory
     */
    public function removeStock(int $quantity)
    {
        if ($quantity > $this->quantity) {
            throw new \Exception('Not enough stock available');
        }
        
        $this->quantity -= $quantity;
        $this->save();
        
        return $this;
    }
    
    /**
     * Get stock value
     */
    public function getStockValue()
    {
        return $this->quantity * $this->product->purchase_price;
    }
    
    /**
     * Scope for low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= alert_threshold');
    }
}

