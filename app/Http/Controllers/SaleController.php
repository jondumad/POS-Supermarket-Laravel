<?php
// app/Http/Controllers/SaleController.php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'user'])->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::where('is_active', true)
            ->with('inventoryItem')
            ->whereHas('inventoryItem', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->get();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'discount' => 'required|array',
            'discount.*' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,debit_card,mobile_payment',
            'notes' => 'nullable|string',
        ]);

        $subtotal = 0;
        $totalDiscount = 0;

        // Validate stock availability and calculate initial amounts
        for ($i = 0; $i < count($validatedData['product_id']); $i++) {
            $productId = $validatedData['product_id'][$i];
            $quantity = $validatedData['quantity'][$i];

            $inventoryItem = InventoryItem::where('product_id', $productId)->first();

            if (!$inventoryItem || $inventoryItem->quantity < $quantity) {
                return back()->with('error', 'Insufficient stock for some products.')->withInput();
            }

            $discount = $validatedData['discount'][$i];
            $unitPrice = $validatedData['unit_price'][$i];
            $itemSubtotal = $unitPrice * $quantity;

            $subtotal += $itemSubtotal;
            $totalDiscount += $discount;
        }

        // Calculate tax (example: 10%)
        $taxRate = 0.10;
        $taxAmount = $subtotal * $taxRate;

        // Calculate total
        $totalAmount = $subtotal + $taxAmount - $totalDiscount;

        DB::beginTransaction();

        try {
            // Create sale
            $sale = Sale::create([
                'invoice_number' => 'INV-' . Str::upper(Str::random(8)),
                'customer_id' => $validatedData['customer_id'] ?? null,
                'user_id' => Auth::check() ? Auth::id() : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'payment_method' => $validatedData['payment_method'],
                'status' => 'completed', // Assuming sales are completed upon creation
                'notes' => $validatedData['notes'] ?? null,
            ]);

            // Create sale items and update inventory
            for ($i = 0; $i < count($validatedData['product_id']); $i++) {
                $productId = $validatedData['product_id'][$i];
                $quantity = $validatedData['quantity'][$i];
                $unitPrice = $validatedData['unit_price'][$i];
                $discount = $validatedData['discount'][$i];
                $itemSubtotal = ($unitPrice * $quantity) - $discount; // Subtotal per item after discount

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'discount' => $discount,
                ]);

                // Update inventory
                $inventoryItem = InventoryItem::where('product_id', $productId)->first();
                $inventoryItem->decrement('quantity', $quantity);
            }

            // Update customer total purchases and loyalty points
            if ($validatedData['customer_id']) {
                $customer = Customer::find($validatedData['customer_id']);
                $customer->increment('total_purchases', $totalAmount);

                // Add loyalty points (example: 1 point per $10 spent)
                $pointsEarned = floor($totalAmount / 10);
                $customer->increment('loyalty_points', $pointsEarned);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function printReceipt(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.receipt', compact('sale'));
    }

    public function void(Sale $sale)
    {
        // Prevent voiding if already cancelled or in a state that shouldn't be voided
        if ($sale->status !== 'completed') {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Only completed sales can be voided.');
        }

        DB::beginTransaction();

        try {
            // Revert inventory quantities
            foreach ($sale->items as $item) {
                $inventoryItem = InventoryItem::where('product_id', $item->product_id)->first();
                if ($inventoryItem) {
                    $inventoryItem->increment('quantity', $item->quantity);
                }
                // Handle case where inventory item might be missing? (Shouldn't happen if data integrity is maintained)
            }

            // Revert customer total purchases and loyalty points
            if ($sale->customer) {
                $sale->customer->decrement('total_purchases', $sale->total_amount);
                // Revert loyalty points (ensure this logic matches the earning logic)
                $pointsReverted = floor($sale->total_amount / 10);
                // Prevent negative loyalty points if needed, depending on business logic
                $newLoyaltyPoints = $sale->customer->loyalty_points - $pointsReverted;
                $sale->customer->update(['loyalty_points' => max(0, $newLoyaltyPoints)]); // Ensure points don't go below 0
            }

            // Update sale status
            $sale->update(['status' => 'cancelled']); // Or 'voided' if that status exists

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale voided successfully and inventory reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while voiding the sale: ' . $e->getMessage());
        }
    }
}
