<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        return $user->can('view orders');
    }

    public function refund(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function void(User $user, Payment $payment): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }
}