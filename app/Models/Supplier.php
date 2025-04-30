<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all purchase orders for this supplier
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    
    /**
     * Get total amount spent with this supplier
     */
    public function getTotalSpent()
    {
        return $this->purchaseOrders()
            ->where('status', 'received')
            ->sum('total_amount');
    }
    
    /**
     * Get products supplied by this supplier
     */
    public function products()
    {
        $productIds = $this->purchaseOrders()
            ->with('items.product')
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('product_id')
            ->unique();
            
        return Product::whereIn('id', $productIds)->get();
    }
}
