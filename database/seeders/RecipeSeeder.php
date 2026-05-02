<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Material;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $nasiGoreng = Product::where('sku', 'FOOD001')->first();
        $cappuccino = Product::where('sku', 'DRINK002')->first();
        $frenchFries = Product::where('sku', 'SNACK001')->first();

        $beras = Material::where('sku', 'MTL001')->first();
        $minyak = Material::where('sku', 'MTL002')->first();
        $telur = Material::where('sku', 'MTL003')->first();
        $ayam = Material::where('sku', 'MTL004')->first();
        $kopi = Material::where('sku', 'MTL005')->first();
        $susu = Material::where('sku', 'MTL006')->first();
        $kentang = Material::where('sku', 'MTL007')->first();

        // Recipe for Nasi Goreng Spesial
        if ($nasiGoreng && $beras) {
            Recipe::create([
                'product_id' => $nasiGoreng->id,
                'material_id' => $beras->id,
                'quantity' => 0.3, // 300gr
            ]);
        }
        
        if ($nasiGoreng && $minyak) {
            Recipe::create([
                'product_id' => $nasiGoreng->id,
                'material_id' => $minyak->id,
                'quantity' => 0.05, // 50ml
            ]);
        }
        
        if ($nasiGoreng && $telur) {
            Recipe::create([
                'product_id' => $nasiGoreng->id,
                'material_id' => $telur->id,
                'quantity' => 2,
            ]);
        }
        
        if ($nasiGoreng && $ayam) {
            Recipe::create([
                'product_id' => $nasiGoreng->id,
                'material_id' => $ayam->id,
                'quantity' => 0.1, // 100gr
            ]);
        }

        // Recipe for Cappuccino
        if ($cappuccino && $kopi) {
            Recipe::create([
                'product_id' => $cappuccino->id,
                'material_id' => $kopi->id,
                'quantity' => 0.02, // 20gr
            ]);
        }
        
        if ($cappuccino && $susu) {
            Recipe::create([
                'product_id' => $cappuccino->id,
                'material_id' => $susu->id,
                'quantity' => 0.2, // 200ml
            ]);
        }

        // Recipe for French Fries
        if ($frenchFries && $kentang) {
            Recipe::create([
                'product_id' => $frenchFries->id,
                'material_id' => $kentang->id,
                'quantity' => 0.2, // 200gr
            ]);
        }
        
        if ($frenchFries && $minyak) {
            Recipe::create([
                'product_id' => $frenchFries->id,
                'material_id' => $minyak->id,
                'quantity' => 0.03, // 30ml
            ]);
        }
    }
}