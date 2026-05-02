<?php

namespace App\Enums;

enum ModifierType: string
{
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';

    public function label(): string
    {
        return match($this) {
            self::SINGLE => 'Pilih Satu',
            self::MULTIPLE => 'Pilih Banyak',
        };
    }
}