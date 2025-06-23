<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Factories\ProductFactory;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        $products = [
            [
                'name' => 'Smartphone X1',
                'sku' => 'SPX1',
                'description' => 'Latest model smartphone with advanced features',
                'purchase_price' => 200.00,
                'selling_price' => 299.99,
                'category_id' => $categories->firstWhere('name', 'Electronics')?->id ?? null,
                'image_path' => 'products/smartphone.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Laptop Pro',
                'sku' => 'LTPRO',
                'description' => 'High-performance laptop for professionals',
                'purchase_price' => 500.00,
                'selling_price' => 799.99,
                'category_id' => $categories->firstWhere('name', 'Electronics')?->id ?? null,
                'image_path' => 'products/laptop.jpg',
                'is_active' => true
            ],
            [
                'name' => 'Coffee Blend',
                'sku' => 'CBLD',
                'description' => 'Premium coffee blend in 500g pack',
                'purchase_price' => 5.00,
                'selling_price' => 9.99,
                'category_id' => $categories->firstWhere('name', 'Food & Beverages')?->id ?? null,
                'image_path' => 'products/coffee.jpg',
                'is_active' => true
            ],
            [
                'name' => 'T-Shirt',
                'sku' => 'TSH01',
                'description' => 'Classic cotton t-shirt',
                'purchase_price' => 3.00,
                'selling_price' => 15.99,
                'category_id' => $categories->firstWhere('name', 'Clothing')?->id ?? null,
                'image_path' => 'products/tshirt.jpg',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create additional random products
        ProductFactory::new()->count(20)->create();
    }
}
