<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@giftshop.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Customer
        User::create([
            'name' => 'Customer User',
            'email' => 'user@giftshop.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'phone' => '089876543210',
        ]);
    }
}