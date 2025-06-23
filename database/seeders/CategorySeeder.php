<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Factories\CategoryFactory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create predefined categories
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Food & Beverages', 'description' => 'Food items and drinks'],
            ['name' => 'Clothing', 'description' => 'Apparel and accessories'],
            ['name' => 'Home & Living', 'description' => 'Home decor and furniture'],
            ['name' => 'Stationery', 'description' => 'Office supplies and stationery items']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create additional random categories
        CategoryFactory::new()->count(10)->create();
    }
}
