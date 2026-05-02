<?php

namespace App\Enums;

enum CashMovementType: string
{
    case CASH_IN = 'cash_in';
    case CASH_OUT = 'cash_out';

    public function label(): string
    {
        return match($this) {
            self::CASH_IN => 'Kas Masuk',
            self::CASH_OUT => 'Kas Keluar',
        };
    }

    public function sign(): int
    {
        return match($this) {
            self::CASH_IN => 1,
            self::CASH_OUT => -1,
        };
    }
}