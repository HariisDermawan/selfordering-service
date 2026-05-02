<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DeliveryOrder;

class DeliveryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function view(User $user, DeliveryOrder $delivery): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager', 'cashier']);
    }

    public function updateStatus(User $user, DeliveryOrder $delivery): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function assignDriver(User $user, DeliveryOrder $delivery): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }
}