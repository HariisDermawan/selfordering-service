<?php

namespace App\Policies;

use App\Models\User;
use App\Models\KitchenTicket;

class KitchenPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view kitchen orders');
    }

    public function view(User $user, KitchenTicket $ticket): bool
    {
        return $user->can('view kitchen orders');
    }

    public function updateStatus(User $user, KitchenTicket $ticket): bool
    {
        return $user->can('update kitchen status');
    }
}