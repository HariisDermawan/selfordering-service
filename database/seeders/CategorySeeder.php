<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'icon' => 'fa-utensils', 'sort_order' => 1],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon' => 'fa-coffee', 'sort_order' => 2],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => 'fa-cookie-bite', 'sort_order' => 3],
            ['name' => 'Dessert', 'slug' => 'dessert', 'icon' => 'fa-ice-cream', 'sort_order' => 4],
            ['name' => 'Paket Hemat', 'slug' => 'paket-hemat', 'icon' => 'fa-box', 'sort_order' => 5],
            ['name' => 'Nasi Goreng', 'slug' => 'nasi-goreng', 'icon' => 'fa-utensils', 'sort_order' => 6],
            ['name' => 'Mie & Pasta', 'slug' => 'mie-pasta', 'icon' => 'fa-utensils', 'sort_order' => 7],
            ['name' => 'Ayam & Daging', 'slug' => 'ayam-daging', 'icon' => 'fa-utensils', 'sort_order' => 8],
            ['name' => 'Coffee', 'slug' => 'coffee', 'icon' => 'fa-coffee', 'sort_order' => 9],
            ['name' => 'Tea', 'slug' => 'tea', 'icon' => 'fa-coffee', 'sort_order' => 10],
            ['name' => 'Juice', 'slug' => 'juice', 'icon' => 'fa-coffee', 'sort_order' => 11],
        ];

        foreach ($categories as $category) {
            Category::create([
                'uuid' => (string) Str::uuid(),
                'name' => $category['name'],
                'slug' => $category['slug'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}