<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Administrator',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');
        $manager = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Manager Resto',
            'email' => 'manager@restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'is_active' => true,
        ]);
        $manager->assignRole('manager');
        $cashier1 = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Pratiwi Aprilia',
            'email' => 'pratiwi@restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'is_active' => true,
        ]);
        $cashier1->assignRole('cashier');
        $cashier2 = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Haris Darmawan',
            'email' => 'harisdermawan@restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '081234567893',
            'is_active' => true,
        ]);
        $cashier2->assignRole('cashier');
        $kitchen = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Chef Didi',
            'email' => 'kitchen@restaurant.com',
            'password' => Hash::make('password'),
            'phone' => '081234567894',
            'is_active' => true,
        ]);
        $kitchen->assignRole('kitchen');
        $customer = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Customer Regular',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567895',
            'is_active' => true,
        ]);
        $customer->assignRole('customer');
    }
}