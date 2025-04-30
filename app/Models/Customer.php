<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'total_purchases',
        'loyalty_points',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_purchases' => 'decimal:2',
        'loyalty_points' => 'integer',
    ];

    /**
     * Get all sales for this customer
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
    /**
     * Calculate and update total purchases for this customer
     */
    public function updateTotalPurchases()
    {
        $this->total_purchases = $this->sales()->where('status', 'completed')->sum('total_amount');
        $this->save();
        
        return $this;
    }
    
    /**
     * Add loyalty points to this customer
     */
    public function addLoyaltyPoints(int $points)
    {
        $this->loyalty_points += $points;
        $this->save();
        
        return $this;
    }
}
