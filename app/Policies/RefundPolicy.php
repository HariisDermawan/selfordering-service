<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Refund;

class RefundPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function approve(User $user, Refund $refund): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function reject(User $user, Refund $refund): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }
}