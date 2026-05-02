<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $permissions = [
            // Product permissions
            'view products', 'create products', 'edit products', 'delete products',
            // Category permissions
            'view categories', 'create categories', 'edit categories', 'delete categories',
            // Order permissions
            'view orders', 'create orders', 'edit orders', 'delete orders', 'process payments',
            // Shift permissions
            'open shift', 'close shift', 'view reports',
            // Kitchen permissions
            'view kitchen orders', 'update kitchen status',
            // Inventory permissions
            'view inventory', 'manage inventory',
            // User & Role permissions
            'manage users', 'manage roles',
            // Promotion permissions
            'manage promotions',
            // Customer permissions
            'view customers', 'manage customers',  // <-- DITAMBAH
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Admin role - gets all permissions
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Manager role
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo([
            'view products', 'view categories', 'view orders', 'process payments',
            'view reports', 'open shift', 'close shift', 'view inventory',
            'view kitchen orders', 'manage promotions', 'view customers', 'manage customers'
        ]);

        // Cashier role
        $cashierRole = Role::create(['name' => 'cashier', 'guard_name' => 'web']);
        $cashierRole->givePermissionTo([
            'view products', 'view categories', 'create orders', 'edit orders', 
            'process payments', 'open shift', 'close shift', 'view customers'
        ]);

        // Kitchen role
        $kitchenRole = Role::create(['name' => 'kitchen', 'guard_name' => 'web']);
        $kitchenRole->givePermissionTo([
            'view kitchen orders', 'update kitchen status'
        ]);

        // Customer role
        $customerRole = Role::create(['name' => 'customer', 'guard_name' => 'web']);
        $customerRole->givePermissionTo([
            'view products', 'create orders'
        ]);
    }
}