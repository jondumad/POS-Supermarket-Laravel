<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'inventoryItem'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        $product = Product::create([
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'description' => $validatedData['description'] ?? null,
            'purchase_price' => $validatedData['purchase_price'],
            'selling_price' => $validatedData['selling_price'],
            'category_id' => $validatedData['category_id'] ?? null,
            'image_path' => $validatedData['image_path'] ?? null,
            'is_active' => $validatedData['is_active'] ?? true,
        ]);

        // Create inventory item
        InventoryItem::create([
            'product_id' => $product->id,
            'quantity' => $validatedData['quantity'],
            'alert_threshold' => $validatedData['alert_threshold'] ?? 10,
            'location' => $validatedData['location'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'inventoryItem']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('inventoryItem');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'alert_threshold' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        $product->update([
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'description' => $validatedData['description'] ?? null,
            'purchase_price' => $validatedData['purchase_price'],
            'selling_price' => $validatedData['selling_price'],
            'category_id' => $validatedData['category_id'] ?? null,
            'image_path' => $validatedData['image_path'] ?? $product->image_path,
            'is_active' => $validatedData['is_active'] ?? true,
        ]);

        // Update inventory item
        $product->inventoryItem()->update([
            'quantity' => $validatedData['quantity'],
            'alert_threshold' => $validatedData['alert_threshold'] ?? 10,
            'location' => $validatedData['location'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
