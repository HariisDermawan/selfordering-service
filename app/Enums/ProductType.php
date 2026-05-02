<?php

namespace App\Enums;

enum ProductType: string
{
    case SINGLE = 'single';
    case VARIANT = 'variant';
    case BUNDLE = 'bundle';

    public function label(): string
    {
        return match($this) {
            self::SINGLE => 'Produk Tunggal',
            self::VARIANT => 'Produk Varian',
            self::BUNDLE => 'Paket Bundle',
        };
    }
}