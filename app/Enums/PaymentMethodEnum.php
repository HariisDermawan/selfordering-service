<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH = 'CASH';
    case OVO = 'OVO';
    case GOPAY = 'GOPAY';
    case QRIS = 'QRIS';
    case CARD = 'CARD';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Tunai',
            self::OVO => 'OVO',
            self::GOPAY => 'GoPay',
            self::QRIS => 'QRIS',
            self::CARD => 'Kartu Kredit',
        };
    }

    public function isCashless(): bool
    {
        return !in_array($this, [self::CASH]);
    }
}