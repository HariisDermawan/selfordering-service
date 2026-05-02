<?php

namespace App\Enums;

enum ShiftStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Shift Dibuka',
            self::CLOSED => 'Shift Ditutup',
        };
    }
}