<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Tunai', 'code' => 'CASH', 'icon' => 'fa-money-bill', 'is_active' => true, 'requires_approval' => false],
            ['name' => 'OVO', 'code' => 'OVO', 'icon' => 'fa-mobile-alt', 'is_active' => true, 'requires_approval' => false],
            ['name' => 'GoPay', 'code' => 'GOPAY', 'icon' => 'fa-mobile-alt', 'is_active' => true, 'requires_approval' => false],
            ['name' => 'QRIS', 'code' => 'QRIS', 'icon' => 'fa-qrcode', 'is_active' => true, 'requires_approval' => false],
            ['name' => 'Kartu Kredit', 'code' => 'CARD', 'icon' => 'fa-credit-card', 'is_active' => true, 'requires_approval' => false],
            ['name' => 'Debit BCA', 'code' => 'DEBIT_BCA', 'icon' => 'fa-credit-card', 'is_active' => true, 'requires_approval' => true],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}