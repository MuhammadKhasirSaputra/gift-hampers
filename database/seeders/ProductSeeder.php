<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori yang sudah di-seed
        $lebaran = Category::where('name', 'Lebaran')->first();
        $valentine = Category::where('name', 'Valentine')->first();
        $natal = Category::where('name', 'Natal')->first();
        $ultah = Category::where('name', 'Ulang Tahun')->first();
        $nikah = Category::where('name', 'Pernikahan')->first();

        $products = [
            [
                'name' => 'Hampers Premium Lebaran',
                'category_id' => $lebaran?->id,
                'description' => 'Paket hampers mewah untuk Lebaran',
                'price' => 500000,
                'stock' => 25,
                'sold' => 45,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Gift Box Valentine',
                'category_id' => $valentine?->id,
                'description' => 'Kado spesial Valentine',
                'price' => 400000,
                'stock' => 18,
                'sold' => 38,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Hampers Natal Deluxe',
                'category_id' => $natal?->id,
                'description' => 'Hampers mewah Natal',
                'price' => 620000,
                'stock' => 12,
                'sold' => 32,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Gift Set Ulang Tahun',
                'category_id' => $ultah?->id,
                'description' => 'Paket kado ulang tahun',
                'price' => 350000,
                'stock' => 30,
                'sold' => 28,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Hampers Pernikahan',
                'category_id' => $nikah?->id,
                'description' => 'Hampers untuk pernikahan',
                'price' => 750000,
                'stock' => 10,
                'sold' => 25,
                'status' => 'Aktif',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}