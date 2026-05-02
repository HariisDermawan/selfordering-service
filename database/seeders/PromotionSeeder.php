<?php

namespace Database\Seeders;

use App\Models\BuyXGetYRule;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Percentage discount promotion
        Promotion::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Diskon Weekend 10%',
            'code' => 'WEEKEND10',
            'type' => 'percentage',
            'discount_value' => 10,
            'min_purchase' => 50000,
            'max_discount' => 50000,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(1),
            'usage_limit' => 1000,
            'is_active' => true,
        ]);

        // Fixed discount promotion
        Promotion::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Diskon Rp20.000',
            'code' => 'DISKON20',
            'type' => 'fixed',
            'discount_value' => 20000,
            'min_purchase' => 100000,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(1),
            'is_active' => true,
        ]);

        // Buy X Get Y promotion
        $promotion = Promotion::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Beli 1 Gratis 1 French Fries',
            'code' => 'BUY1GET1',
            'type' => 'buy_x_get_y',
            'discount_value' => 0,
            'min_purchase' => 0,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(1),
            'is_active' => true,
        ]);

        $frenchFries = Product::where('sku', 'SNACK001')->first();
        if ($promotion && $frenchFries) {
            BuyXGetYRule::create([
                'promotion_id' => $promotion->id,
                'buy_product_id' => $frenchFries->id,
                'buy_quantity' => 1,
                'get_product_id' => $frenchFries->id,
                'get_quantity' => 1,
            ]);
        }

        // Vouchers
        Voucher::create([
            'code' => 'VOUCHER10K',
            'type' => 'fixed',
            'value' => 10000,
            'expires_at' => Carbon::now()->addMonths(1),
            'min_purchase' => 50000,
            'usage_limit' => 100,
        ]);

        Voucher::create([
            'code' => 'VOUCHER15PCT',
            'type' => 'percentage',
            'value' => 15,
            'expires_at' => Carbon::now()->addMonths(1),
            'min_purchase' => 100000,
            'usage_limit' => 50,
        ]);
    }
}