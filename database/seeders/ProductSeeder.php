<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $nasiGorengCat = Category::where('slug', 'nasi-goreng')->first();
        $coffeeCat = Category::where('slug', 'coffee')->first();
        $snackCat = Category::where('slug', 'snack')->first();
        
        // Products
        $products = [
            [
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, udang, dan sayuran',
                'category_id' => $nasiGorengCat?->id,
                'price' => 35000,
                'cost' => 15000,
                'stock' => 100,
                'min_stock' => 10,
                'sku' => 'FOOD001',
                'type' => 'single',
            ],
            [
                'name' => 'Nasi Goreng Seafood',
                'description' => 'Nasi goreng dengan seafood campur',
                'category_id' => $nasiGorengCat?->id,
                'price' => 45000,
                'cost' => 20000,
                'stock' => 80,
                'min_stock' => 10,
                'sku' => 'FOOD002',
                'type' => 'single',
            ],
            [
                'name' => 'Espresso',
                'description' => 'Kopi espresso murni',
                'category_id' => $coffeeCat?->id,
                'price' => 18000,
                'cost' => 5000,
                'stock' => 200,
                'min_stock' => 20,
                'sku' => 'DRINK001',
                'type' => 'variant',
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Kopi cappuccino dengan foam susu',
                'category_id' => $coffeeCat?->id,
                'price' => 25000,
                'cost' => 8000,
                'stock' => 150,
                'min_stock' => 15,
                'sku' => 'DRINK002',
                'type' => 'variant',
            ],
            [
                'name' => 'French Fries',
                'description' => 'Kentang goreng dengan saus tomat',
                'category_id' => $snackCat?->id,
                'price' => 15000,
                'cost' => 5000,
                'stock' => 300,
                'min_stock' => 30,
                'sku' => 'SNACK001',
                'type' => 'single',
            ],
            [
                'name' => 'Chicken Wings',
                'description' => 'Sayap ayam goreng dengan saus pedas manis',
                'category_id' => $snackCat?->id,
                'price' => 28000,
                'cost' => 12000,
                'stock' => 100,
                'min_stock' => 15,
                'sku' => 'SNACK002',
                'type' => 'single',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'uuid' => (string) Str::uuid(),  // <-- TAMBAHKAN INI
                'name' => $product['name'],
                'description' => $product['description'],
                'category_id' => $product['category_id'],
                'price' => $product['price'],
                'cost' => $product['cost'],
                'stock' => $product['stock'],
                'min_stock' => $product['min_stock'],
                'sku' => $product['sku'],
                'type' => $product['type'],
                'is_active' => true,
                'is_available' => true,
            ]);
        }

        // Product variants for Cappuccino
        $cappuccino = Product::where('sku', 'DRINK002')->first();
        if ($cappuccino) {
            ProductVariant::create([
                'product_id' => $cappuccino->id,
                'name' => 'Small',
                'sku' => 'DRINK002-SML',
                'additional_price' => 0,
                'stock' => 100,
                'is_active' => true,
            ]);
            
            ProductVariant::create([
                'product_id' => $cappuccino->id,
                'name' => 'Medium',
                'sku' => 'DRINK002-MED',
                'additional_price' => 5000,
                'stock' => 80,
                'is_active' => true,
            ]);
            
            ProductVariant::create([
                'product_id' => $cappuccino->id,
                'name' => 'Large',
                'sku' => 'DRINK002-LRG',
                'additional_price' => 10000,
                'stock' => 50,
                'is_active' => true,
            ]);
        }
    }
}