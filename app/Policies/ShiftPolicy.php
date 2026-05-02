<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shift;

class ShiftPolicy
{
    public function open(User $user): bool
    {
        return $user->can('open shift');
    }

    public function close(User $user, Shift $shift): bool
    {
        if ($shift->user_id !== $user->id && !$user->hasRole('admin')) {
            return false;
        }
        return $user->can('close shift');
    }

    public function viewXReport(User $user, Shift $shift): bool
    {
        return $user->can('view reports');
    }

    public function viewZReport(User $user, Shift $shift): bool
    {
        return $user->can('view reports');
    }

    public function cashMovement(User $user, Shift $shift): bool
    {
        return $shift->status === 'open' && $user->hasRole(['admin', 'manager', 'cashier']);
    }
}