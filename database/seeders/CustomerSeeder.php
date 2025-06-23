<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Database\Factories\CustomerFactory;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1234567890',
                'address' => '123 Main St, Anytown, USA',
                'is_active' => true
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'phone' => '+0987654321',
                'address' => '456 Oak Ave, Anytown, USA',
                'is_active' => true
            ],
            [
                'name' => 'Business Customer',
                'email' => 'business@example.com',
                'phone' => '+1122334455',
                'address' => '789 Business Park, Anytown, USA',
                'is_active' => true,
                'is_business' => true
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create additional random customers
        CustomerFactory::new()->count(20)->create();
    }
}
