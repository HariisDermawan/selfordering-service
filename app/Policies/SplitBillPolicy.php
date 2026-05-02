<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SplitBill;
use App\Models\Order;

class SplitBillPolicy
{
    public function split(User $user, Order $order): bool
    {
        return $order->payment_status === 'unpaid' 
            && $user->hasRole(['admin', 'manager', 'cashier']);
    }

    public function pay(User $user, SplitBill $splitBill): bool
    {
        return $splitBill->status === 'pending' 
            && $user->can('process payments');
    }
}