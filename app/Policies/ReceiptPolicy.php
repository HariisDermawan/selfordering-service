<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receipt;

class ReceiptPolicy
{
    public function view(User $user, Receipt $receipt): bool
    {
        return true;
    }

    public function reprint(User $user, Receipt $receipt): bool
    {
        return $user->hasRole(['admin', 'manager', 'cashier']);
    }
}