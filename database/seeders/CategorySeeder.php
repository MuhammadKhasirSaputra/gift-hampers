<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Lebaran', 'Valentine', 'Natal', 'Ulang Tahun', 'Pernikahan'];
        
        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => \Illuminate\Support\Str::slug($name)]
            );
        }
    }
}