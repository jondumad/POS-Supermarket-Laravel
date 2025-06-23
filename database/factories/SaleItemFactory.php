<?php

namespace Database\Factories;

use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::all()->random()->id,
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_price' => function (array $attributes) {
                return Product::find($attributes['product_id'])->selling_price;
            },
            'discount' => $this->faker->randomFloat(2, 0, 10),
            'subtotal' => function (array $attributes) {
                return ($attributes['unit_price'] - $attributes['discount']) * $attributes['quantity'];
            }
        ];
    }

    /**
     * Configure the factory to create sale items for a specific sale.
     */
    public function saleItems()
    {
        return $this->state(function (array $attributes) {
            return [
                'product_id' => Product::all()->random()->id,
                'quantity' => $this->faker->numberBetween(1, 3),
                'unit_price' => function (array $attributes) {
                    return Product::find($attributes['product_id'])->selling_price;
                },
                'discount' => $this->faker->randomFloat(2, 0, 5),
                'subtotal' => function (array $attributes) {
                    return ($attributes['unit_price'] - $attributes['discount']) * $attributes['quantity'];
                }
            ];
        });
    }
}
