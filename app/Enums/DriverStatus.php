<?php

namespace App\Enums;

enum DriverStatus: string
{
    case AVAILABLE = 'available';
    case BUSY = 'busy';
    case OFFLINE = 'offline';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Tersedia',
            self::BUSY => 'Sedang Bertugas',
            self::OFFLINE => 'Offline',
        };
    }
}