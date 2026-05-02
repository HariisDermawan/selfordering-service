<?php

namespace App\Enums;

enum VoidType: string
{
    case FULL = 'full';
    case PARTIAL = 'partial';

    public function label(): string
    {
        return match($this) {
            self::FULL => 'Void Seluruh Transaksi',
            self::PARTIAL => 'Void Sebagian Item',
        };
    }
}