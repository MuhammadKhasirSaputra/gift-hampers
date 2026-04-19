<?php
namespace Database\Seeders;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Hampers Premium Lebaran', 'category' => 'Lebaran', 'price' => 500000, 'stock' => 25, 'sold' => 45],
            ['name' => 'Gift Box Valentine', 'category' => 'Valentine', 'price' => 400000, 'stock' => 18, 'sold' => 38],
            ['name' => 'Hampers Natal Deluxe', 'category' => 'Natal', 'price' => 620000, 'stock' => 12, 'sold' => 32],
            ['name' => 'Gift Set Ulang Tahun', 'category' => 'Ulang Tahun', 'price' => 350000, 'stock' => 30, 'sold' => 28],
            ['name' => 'Hampers Pernikahan', 'category' => 'Pernikahan', 'price' => 750000, 'stock' => 10, 'sold' => 25],
        ];

        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();
            
            Product::firstOrCreate(
                ['name' => $prod['name']],
                [
                    'category_id' => $category?->id,
                    'description' => 'Paket hampers untuk ' . $prod['category'],
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'sold' => $prod['sold'],
                    'status' => 'Aktif',
                ]
            );
        }
    }
}