<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Database\Factories\SaleFactory;
use Database\Factories\SaleItemFactory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create predefined sales
        $sales = [
            [
                'invoice_number' => 'INV-00001',
                'customer_id' => Customer::firstWhere('name', 'John Smith')?->id ?? Customer::inRandomOrder()->first()->id,
                'user_id' => User::firstWhere('name', 'Admin User')?->id ?? User::inRandomOrder()->first()->id,
                'status' => 'completed',
                'subtotal' => 150.00,
                'discount_amount' => 15.00,
                'tax_amount' => 12.00,
                'total_amount' => 147.00,
                'payment_method' => 'cash',
                'created_at' => now()->subDays(5)
            ],
            [
                'invoice_number' => 'INV-00002',
                'customer_id' => Customer::firstWhere('name', 'Business Customer')?->id ?? Customer::inRandomOrder()->first()->id,
                'user_id' => User::firstWhere('name', 'Admin User')?->id ?? User::inRandomOrder()->first()->id,
                'status' => 'completed',
                'subtotal' => 250.00,
                'discount_amount' => 25.00,
                'tax_amount' => 20.00,
                'total_amount' => 245.00,
                'payment_method' => 'credit_card',
                'created_at' => now()->subDays(3)
            ]
        ];

        foreach ($sales as $sale) {
            $saleRecord = Sale::create($sale);
            
            // Add items to the sale
            $items = [
                [
                    'product_id' => Product::firstWhere('name', 'Smartphone X1')?->id ?? Product::inRandomOrder()->first()->id,
                    'quantity' => 1,
                    'unit_price' => 299.99,
                    'discount' => 0,
                    'subtotal' => 299.99
                ],
                [
                    'product_id' => Product::firstWhere('name', 'Coffee Blend')?->id ?? Product::inRandomOrder()->first()->id,
                    'quantity' => 2,
                    'unit_price' => 9.99,
                    'discount' => 0,
                    'subtotal' => 19.98
                ]
            ];

            foreach ($items as $item) {
                $saleRecord->items()->create($item);
            }
        }

        // Create additional random sales
        SaleFactory::new()
            ->count(50)
            ->sequence(
                fn (Sequence $sequence) => [
                    'created_at' => now()->subDays($sequence->index),
                ]
            )
            ->has(
                SaleItemFactory::new()->count(3),
                'items'
            )
            ->create();
    }
}
