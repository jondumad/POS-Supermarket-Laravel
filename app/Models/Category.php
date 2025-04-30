<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * Get the total number of products in this category
     */
    public function getProductCount()
    {
        return $this->products()->count();
    }
    
    /**
     * Get the total stock value of all products in this category
     */
    public function getTotalStockValue()
    {
        $total = 0;
        
        foreach ($this->products as $product) {
            if ($product->inventoryItem) {
                $total += $product->purchase_price * $product->inventoryItem->quantity;
            }
        }
        
        return $total;
    }
}
