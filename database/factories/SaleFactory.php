<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->numberBetween(10000, 99999),
            'customer_id' => Customer::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'subtotal' => $this->faker->randomFloat(2, 100, 1000),
            'discount_amount' => $this->faker->randomFloat(2, 0, 50),
            'tax_amount' => $this->faker->randomFloat(2, 0, 50),
            'total_amount' => function (array $attributes) {
                return $attributes['subtotal'] - $attributes['discount_amount'] + $attributes['tax_amount'];
            },
            'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'debit_card', 'mobile_payment']),
        ];
    }
}
