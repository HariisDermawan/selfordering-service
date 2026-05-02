<?php

namespace App\Enums;

enum TaxType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function label(): string
    {
        return match($this) {
            self::PERCENTAGE => 'Persentase (%)',
            self::FIXED => 'Nominal Tetap',
        };
    }

    public function calculate(float $amount, float $rate): float
    {
        return match($this) {
            self::PERCENTAGE => round($amount * ($rate / 100), 2),
            self::FIXED => $rate,
        };
    }
}