<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Factories\UserFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@posapp.com',
            'password' => Hash::make('password'),
            'is_admin' => true
        ]);

        // Create regular users
        UserFactory::new()
            ->count(5)
            ->sequence(
                ['email' => 'staff1@posapp.com'],
                ['email' => 'staff2@posapp.com'],
                ['email' => 'staff3@posapp.com'],
                ['email' => 'staff4@posapp.com'],
                ['email' => 'staff5@posapp.com']
            )
            ->create();
    }
}
