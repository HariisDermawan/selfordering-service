<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = User::where('email', 'cashier1@restaurant.com')->first();
        $table = Table::where('table_number', 'T1')->first();
        $product1 = Product::where('sku', 'FOOD001')->first();
        $product2 = Product::where('sku', 'DRINK002')->first();
        $shift = Shift::where('status', 'open')->first();

        if ($cashier && $product1 && $shift) {
            // Completed order
            $order1 = Order::create([
                'uuid' => (string) Str::uuid(),
                'order_number' => 'ORD-' . Carbon::today()->format('Ymd') . '-001',
                'order_type' => 'dine_in',
                'table_id' => $table?->id,
                'user_id' => $cashier->id,
                'shift_id' => $shift->id,
                'status' => 'completed',
                'payment_status' => 'paid',
                'subtotal' => 60000,
                'discount_amount' => 0,
                'tax_amount' => 6000,
                'service_charge' => 3000,
                'total' => 69000,
                'paid_amount' => 69000,
                'ordered_at' => Carbon::today()->setTime(12, 0),
                'completed_at' => Carbon::today()->setTime(12, 30),
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product1->id,
                'quantity' => 1,
                'unit_price' => 35000,
                'subtotal' => 35000,
                'total' => 35000,
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product2->id,
                'quantity' => 1,
                'unit_price' => 25000,
                'subtotal' => 25000,
                'total' => 25000,
            ]);

            // Pending order
            $order2 = Order::create([
                'uuid' => (string) Str::uuid(),
                'order_number' => 'ORD-' . Carbon::today()->format('Ymd') . '-002',
                'order_type' => 'takeaway',
                'user_id' => $cashier->id,
                'shift_id' => $shift->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'subtotal' => 45000,
                'discount_amount' => 0,
                'tax_amount' => 4500,
                'service_charge' => 2250,
                'total' => 51750,
                'paid_amount' => 0,
                'ordered_at' => Carbon::today()->setTime(13, 0),
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $product1->id,
                'quantity' => 1,
                'unit_price' => 35000,
                'subtotal' => 35000,
                'total' => 35000,
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $product1->id,
                'quantity' => 1,
                'unit_price' => 35000,
                'subtotal' => 35000,
                'total' => 35000,
            ]);
        }
    }
}