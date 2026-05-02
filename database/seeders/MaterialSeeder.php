<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            [
                'name' => 'Beras',
                'sku' => 'MTL001',
                'unit' => 'kg',
                'stock' => 100,
                'min_stock' => 20,
                'unit_price' => 15000,
            ],
            [
                'name' => 'Minyak Goreng',
                'sku' => 'MTL002',
                'unit' => 'liter',
                'stock' => 50,
                'min_stock' => 10,
                'unit_price' => 18000,
            ],
            [
                'name' => 'Telur Ayam',
                'sku' => 'MTL003',
                'unit' => 'pcs',
                'stock' => 500,
                'min_stock' => 100,
                'unit_price' => 3000,
            ],
            [
                'name' => 'Daging Ayam',
                'sku' => 'MTL004',
                'unit' => 'kg',
                'stock' => 50,
                'min_stock' => 10,
                'unit_price' => 40000,
            ],
            [
                'name' => 'Biji Kopi',
                'sku' => 'MTL005',
                'unit' => 'kg',
                'stock' => 30,
                'min_stock' => 5,
                'unit_price' => 120000,
            ],
            [
                'name' => 'Susu UHT',
                'sku' => 'MTL006',
                'unit' => 'liter',
                'stock' => 40,
                'min_stock' => 10,
                'unit_price' => 20000,
            ],
            [
                'name' => 'Kentang',
                'sku' => 'MTL007',
                'unit' => 'kg',
                'stock' => 80,
                'min_stock' => 15,
                'unit_price' => 12000,
            ],
            [
                'name' => 'Udang',
                'sku' => 'MTL008',
                'unit' => 'kg',
                'stock' => 30,
                'min_stock' => 5,
                'unit_price' => 80000,
            ],
            [
                'name' => 'Sayuran',
                'sku' => 'MTL009',
                'unit' => 'kg',
                'stock' => 40,
                'min_stock' => 10,
                'unit_price' => 15000,
            ],
            [
                'name' => 'Gula',
                'sku' => 'MTL010',
                'unit' => 'kg',
                'stock' => 50,
                'min_stock' => 10,
                'unit_price' => 15000,
            ],
        ];

        foreach ($materials as $material) {
            Material::create([
                'uuid' => (string) Str::uuid(),  // <-- TAMBAHKAN INI
                'name' => $material['name'],
                'sku' => $material['sku'],
                'unit' => $material['unit'],
                'stock' => $material['stock'],
                'min_stock' => $material['min_stock'],
                'unit_price' => $material['unit_price'],
            ]);
        }
    }
}