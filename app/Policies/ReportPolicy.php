<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    public function viewDailyReport(User $user): bool
    {
        return $user->can('view reports');
    }

    public function viewSalesReport(User $user): bool
    {
        return $user->can('view reports');
    }

    public function viewTopProducts(User $user): bool
    {
        return $user->can('view reports');
    }

    public function exportReport(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }
}