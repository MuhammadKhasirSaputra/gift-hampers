<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Lebaran'],
            ['name' => 'Valentine'],
            ['name' => 'Natal'],
            ['name' => 'Ulang Tahun'],
            ['name' => 'Pernikahan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}