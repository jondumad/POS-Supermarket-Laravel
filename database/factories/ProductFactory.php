<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'sku' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
            'purchase_price' => fake()->randomFloat(2, 1, 100),
            'selling_price' => fake()->randomFloat(2, 10, 200),
            'category_id' => Category::all()->random()->id,
            'image_path' => 'products/' . fake()->word() . '.jpg',
            'is_active' => fake()->boolean(),
        ];
    }
}
