<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the customer for this sale
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user (cashier) who created this sale
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for this sale
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $date = now()->format('Ymd');
        $lastSale = self::whereDate('created_at', now())->latest()->first();

        $sequence = 1;
        if ($lastSale) {
            $lastInvoice = $lastSale->invoice_number;
            $lastSequence = (int) substr($lastInvoice, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate and update totals
     */
    public function calculateTotals($taxRate = 0.1)
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax_amount = $this->subtotal * $taxRate;
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();

        return $this;
    }

    /**
     * Apply discount amount to the sale
     */
    public function applyDiscount($amount)
    {
        $this->discount_amount = $amount;
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();

        return $this;
    }

    /**
     * Apply percentage discount to the sale
     */
    public function applyDiscountPercentage($percentage)
    {
        $amount = $this->subtotal * ($percentage / 100);
        return $this->applyDiscount($amount);
    }

    /**
     * Mark sale as completed and update inventory
     */
    public function complete()
    {
        if ($this->status === 'completed') {
            return $this;
        }

        DB::transaction(function () {
            foreach ($this->items as $item) {
                $inventoryItem = $item->product->inventoryItem;
                if ($inventoryItem) {
                    $inventoryItem->decrement('quantity', $item->quantity);
                }
            }

            $this->status = 'completed';
            $this->save();
        });

        return $this;
    }
}
