<?php

namespace App\Enums;

enum SplitBillStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Belum Dibayar',
            self::PAID => 'Lunas',
        };
    }
}