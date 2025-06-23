<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Database\Factories\SupplierFactory;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Tech Supply Co.',
                'email' => 'sales@techsupply.com',
                'phone' => '+1234567890',
                'address' => '123 Tech Ave, Supplier City, USA',
                'contact_person' => 'John Smith',
                'is_active' => true
            ],
            [
                'name' => 'Food Wholesale',
                'email' => 'info@foodwholesale.com',
                'phone' => '+0987654321',
                'address' => '456 Food St, Supplier City, USA',
                'contact_person' => 'Jane Doe',
                'is_active' => true
            ],
            [
                'name' => 'Fashion Wholesale',
                'email' => 'sales@fashionwholesale.com',
                'phone' => '+1122334455',
                'address' => '789 Fashion Blvd, Supplier City, USA',
                'contact_person' => 'Mike Johnson',
                'is_active' => true
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create additional random suppliers
        SupplierFactory::new()->count(10)->create();
    }
}
