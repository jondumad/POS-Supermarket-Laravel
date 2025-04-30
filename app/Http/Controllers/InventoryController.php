<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = InventoryItem::with('product')->get();
        return view('inventory.index', compact('inventoryItems'));
    }

    public function edit(InventoryItem $inventory)
    {
        $inventory->load('product');
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);
        
        $inventory->update($validatedData);
        
        return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
    }

    public function lowStock()
    {
        $lowStockItems = InventoryItem::whereRaw('quantity <= alert_threshold')
            ->with('product')
            ->get();
            
        return view('inventory.low_stock', compact('lowStockItems'));
    }
    
    public function adjustStock(Request $request, InventoryItem $inventory)
    {
        $validatedData = $request->validate([
            'adjustment' => 'required|integer',
            'notes' => 'nullable|string',
        ]);
        
        $newQuantity = $inventory->quantity + $validatedData['adjustment'];
        
        if ($newQuantity < 0) {
            return back()->with('error', 'Stock cannot be negative.');
        }
        
        $inventory->update(['quantity' => $newQuantity]);
        
        // You can also log this adjustment in a stock_adjustments table if needed
        
        return redirect()->route('inventory.index')->with('success', 'Stock adjusted successfully.');
    }
}