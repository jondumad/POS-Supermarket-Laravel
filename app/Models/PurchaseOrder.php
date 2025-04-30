<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_number',
        'supplier_id',
        'user_id',
        'total_amount',
        'status',
        'order_date',
        'delivery_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the supplier for this purchase order
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created this purchase order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for this purchase order
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    
    /**
     * Generate a unique reference number
     */
    public static function generateReferenceNumber()
    {
        $prefix = 'PO-';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        
        $sequence = 1;
        if ($lastOrder) {
            $lastReference = $lastOrder->reference_number;
            $lastSequence = (int) substr($lastReference, -4);
            $sequence = $lastSequence + 1;
        }
        
        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calculate and update total amount
     */
    public function calculateTotal()
    {
        $this->total_amount = $this->items->sum('subtotal');
        $this->save();
        
        return $this;
    }
    
    /**
     * Mark purchase order as received and update inventory
     */
    public function markAsReceived()
    {
        if ($this->status === 'received') {
            return $this;
        }
        
        DB::transaction(function () {
            foreach ($this->items as $item) {
                $inventoryItem = $item->product->inventoryItem;
                if ($inventoryItem) {
                    $inventoryItem->addStock($item->quantity);
                } else {
                    InventoryItem::create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'alert_threshold' => 10,
                    ]);
                }
            }
            
            $this->status = 'received';
            $this->delivery_date = now();
            $this->save();
        });
        
        return $this;
    }
    
    /**
     * Scope for pending purchase orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope for received purchase orders
     */
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}
