<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Table;

class TablePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Table $table): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function update(User $user, Table $table): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function delete(User $user, Table $table): bool
    {
        return $user->hasRole(['admin']);
    }
}