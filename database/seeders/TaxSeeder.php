<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            [
                'name' => 'Pajak PB1',
                'code' => 'PB1',
                'rate' => 10,
                'type' => 'percentage',
                'is_active' => true,
            ],
            [
                'name' => 'Service Charge',
                'code' => 'SERVICE_CHARGE',
                'rate' => 5,
                'type' => 'percentage',
                'is_active' => true,
            ],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }
    }
}