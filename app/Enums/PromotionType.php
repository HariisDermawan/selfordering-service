<?php

namespace App\Enums;

enum PromotionType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';
    case BUY_X_GET_Y = 'buy_x_get_y';
    case BUNDLE = 'bundle';

    public function label(): string
    {
        return match($this) {
            self::PERCENTAGE => 'Diskon Persen',
            self::FIXED => 'Diskon Nominal',
            self::BUY_X_GET_Y => 'Beli X Dapat Y',
            self::BUNDLE => 'Paket Bundle',
        };
    }
}