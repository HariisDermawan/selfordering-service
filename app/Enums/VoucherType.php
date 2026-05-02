<?php

namespace App\Enums;

enum VoucherType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function label(): string
    {
        return match($this) {
            self::PERCENTAGE => 'Voucher Persen',
            self::FIXED => 'Voucher Nominal',
        };
    }
}