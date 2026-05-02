<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = User::where('email', 'cashier1@restaurant.com')->first();
        
        if ($cashier) {
            // Today's shift
            Shift::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $cashier->id,
                'opened_at' => Carbon::today()->setTime(8, 0),
                'opening_balance' => 500000,
                'status' => 'open',
                'notes' => 'Morning shift',
            ]);
            
            // Yesterday's shift (closed)
            Shift::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $cashier->id,
                'opened_at' => Carbon::yesterday()->setTime(8, 0),
                'closed_at' => Carbon::yesterday()->setTime(17, 0),
                'opening_balance' => 500000,
                'closing_balance' => 1500000,
                'cash_sales' => 800000,
                'non_cash_sales' => 700000,
                'total_sales' => 1500000,
                'status' => 'closed',
                'notes' => 'Complete shift',
            ]);
        }
    }
}