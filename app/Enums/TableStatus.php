<?php

namespace App\Enums;

enum TableStatus: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
    case RESERVED = 'reserved';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Tersedia',
            self::OCCUPIED => 'Terisi',
            self::RESERVED => 'Reservasi',
            self::MAINTENANCE => 'Perbaikan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::AVAILABLE => 'success',
            self::OCCUPIED => 'danger',
            self::RESERVED => 'warning',
            self::MAINTENANCE => 'secondary',
        };
    }
}