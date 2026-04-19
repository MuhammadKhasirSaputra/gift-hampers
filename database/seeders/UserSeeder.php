<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@giftshop.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@giftshop.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '089876543210',
            ]
        );
    }
}