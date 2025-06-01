<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'user'])->get();
        return view('purchase_orders', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('purchase_orders.form', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference_number' => 'required|string|unique:purchase_orders',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Create the purchase order
            $userId = Auth::id() ?? 1; // Use a default user ID (e.g., admin ID) if no user is logged in

            $purchaseOrder = PurchaseOrder::create([
                'reference_number' => $validatedData['reference_number'],
                'supplier_id' => $validatedData['supplier_id'],
                'user_id' => $userId,
                'total_amount' => 0, // Will calculate later
                'status' => 'pending',
                'order_date' => $validatedData['order_date'],
                'delivery_date' => $validatedData['delivery_date'],
                'notes' => $validatedData['notes'],
            ]);

            $totalAmount = 0;

            // Process each item
            foreach ($validatedData['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Update the total amount
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('error', 'Only pending purchase orders can be edited.');
        }

        $purchaseOrder->load(['items.product']);
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('purchase_orders.form', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('error', 'Only pending purchase orders can be edited.');
        }

        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'reference_number' => 'required|string|unique:purchase_orders,reference_number,' . $purchaseOrder->id,
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;

        for ($i = 0; $i < count($validatedData['product_id']); $i++) {
            $totalAmount += $validatedData['quantity'][$i] * $validatedData['unit_price'][$i];
        }

        DB::beginTransaction();

        try {
            $purchaseOrder->update([
                'reference_number' => $validatedData['reference_number'],
                'supplier_id' => $validatedData['supplier_id'],
                'total_amount' => $totalAmount,
                'order_date' => $validatedData['order_date'],
                'delivery_date' => $validatedData['delivery_date'],
                'notes' => $validatedData['notes'],
            ]);

            // Delete existing items
            $purchaseOrder->items()->delete();

            // Create new items
            for ($i = 0; $i < count($validatedData['product_id']); $i++) {
                $subtotal = $validatedData['quantity'][$i] * $validatedData['unit_price'][$i];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $validatedData['product_id'][$i],
                    'quantity' => $validatedData['quantity'][$i],
                    'unit_price' => $validatedData['unit_price'][$i],
                    'subtotal' => $subtotal,
                ]);
            }

            DB::commit();
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('error', 'Only pending purchase orders can be received.');
        }

        $validatedData = $request->validate([
            'received_quantity' => 'required|array',
            'received_quantity.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchaseOrder->update([
                'status' => 'received',
                'delivery_date' => now(),
            ]);

            foreach ($purchaseOrder->items as $index => $item) {
                $receivedQuantity = $validatedData['received_quantity'][$index];

                // Update inventory
                $inventoryItem = InventoryItem::where('product_id', $item->product_id)->first();

                if ($inventoryItem) {
                    $inventoryItem->increment('quantity', $receivedQuantity);
                } else {
                    InventoryItem::create([
                        'product_id' => $item->product_id,
                        'quantity' => $receivedQuantity,
                        'alert_threshold' => 10,
                    ]);
                }

                // Update product cost if needed
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->purchase_price = $item->unit_price;
                    $product->save();
                }
            }

            DB::commit();
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('success', 'Purchase order received and inventory updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('purchase_orders.show', $purchaseOrder)
                ->with('error', 'Only pending purchase orders can be cancelled.');
        }

        $purchaseOrder->update(['status' => 'cancelled']);

        return redirect()->route('purchase_orders.show', $purchaseOrder)
            ->with('success', 'Purchase order cancelled successfully.');
    }
}
