<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole(['admin']);
    }
}