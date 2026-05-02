<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Promotion;

class PromotionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Promotion $promotion): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('manage promotions');
    }

    public function update(User $user, Promotion $promotion): bool
    {
        return $user->can('manage promotions');
    }

    public function delete(User $user, Promotion $promotion): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function apply(User $user, Promotion $promotion): bool
    {
        return $promotion->isValid();
    }
}