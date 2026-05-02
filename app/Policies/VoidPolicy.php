<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoidTransaction;

class VoidPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function approve(User $user, VoidTransaction $void): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function reject(User $user, VoidTransaction $void): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }
}