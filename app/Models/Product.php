<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'category_id',
        'image_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that this product belongs to
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the inventory item for this product
     */
    public function inventoryItem()
    {
        return $this->hasOne(InventoryItem::class);
    }

    /**
     * Get all sale items for this product
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get all purchase order items for this product
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Get current stock quantity
     */
    public function getCurrentStock()
    {
        return $this->inventoryItem ? $this->inventoryItem->quantity : 0;
    }

    /**
     * Check if product is low on stock
     */
    public function isLowOnStock()
    {
        if (!$this->inventoryItem) {
            return true;
        }

        return $this->inventoryItem->quantity <= $this->inventoryItem->alert_threshold;
    }

    /**
     * Get profit margin percentage
     */
    public function getProfitMarginPercentage()
    {
        if ($this->purchase_price <= 0) {
            return 0;
        }

        $profit = $this->selling_price - $this->purchase_price;
        return round(($profit / $this->purchase_price) * 100, 2);
    }

    /**
     * Get total sales quantity
     */
    public function getTotalSalesQuantity()
    {
        return $this->saleItems()
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('quantity');
    }

    /**
     * Get total sales revenue
     */
    public function getTotalSalesRevenue()
    {
        return $this->saleItems()
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed');
            })
            ->sum('subtotal');
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
