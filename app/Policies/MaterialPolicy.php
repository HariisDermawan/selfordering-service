<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;

class MaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view inventory');
    }

    public function view(User $user, Material $material): bool
    {
        return $user->can('view inventory');
    }

    public function create(User $user): bool
    {
        return $user->can('manage inventory');
    }

    public function update(User $user, Material $material): bool
    {
        return $user->can('manage inventory');
    }

    public function delete(User $user, Material $material): bool
    {
        return $user->hasRole(['admin']);
    }

    public function addStock(User $user, Material $material): bool
    {
        return $user->can('manage inventory');
    }
}