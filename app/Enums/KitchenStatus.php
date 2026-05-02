<?php

namespace App\Enums;

enum KitchenStatus: string
{
    case PENDING = 'pending';
    case COOKING = 'cooking';
    case READY = 'ready';
    case SERVED = 'served';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu',
            self::COOKING => 'Sedang Dimasak',
            self::READY => 'Siap Saji',
            self::SERVED => 'Telah Disajikan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::COOKING => 'info',
            self::READY => 'success',
            self::SERVED => 'primary',
        };
    }

    public function next(): ?self
    {
        return match($this) {
            self::PENDING => self::COOKING,
            self::COOKING => self::READY,
            self::READY => self::SERVED,
            self::SERVED => null,
        };
    }
}