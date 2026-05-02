<?php

namespace App\Enums;

enum StockMovementType: string
{
    case IN = 'in';
    case OUT = 'out';
    case ADJUSTMENT = 'adjustment';

    public function label(): string
    {
        return match($this) {
            self::IN => 'Stock Masuk',
            self::OUT => 'Stock Keluar',
            self::ADJUSTMENT => 'Penyesuaian Stock',
        };
    }

    public function sign(): int
    {
        return match($this) {
            self::IN => 1,
            self::OUT => -1,
            self::ADJUSTMENT => 0,
        };
    }
}