<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view orders');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('view orders');
    }

    public function create(User $user): bool
    {
        return $user->can('create orders');
    }

    public function update(User $user, Order $order): bool
    {
        if (in_array($order->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED])) {
            return false;
        }
        return $user->can('edit orders');
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole(['admin']) && $order->status === OrderStatus::PENDING;
    }

    public function processPayment(User $user, Order $order): bool
    {
        if ($order->payment_status === 'paid') {
            return false;
        }
        return $user->can('process payments');
    }

    public function cancel(User $user, Order $order): bool
    {
        return in_array($order->status, [OrderStatus::PENDING, OrderStatus::PROCESSING]) 
            && $user->hasRole(['admin', 'manager', 'cashier']);
    }
}