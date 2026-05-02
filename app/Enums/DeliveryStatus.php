<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case PENDING = 'pending';
    case PICKED_UP = 'picked_up';
    case ON_DELIVERY = 'on_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Driver',
            self::PICKED_UP => 'Pesanan Diambil',
            self::ON_DELIVERY => 'Sedang Dikirim',
            self::DELIVERED => 'Telah Diterima',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::PICKED_UP => 'info',
            self::ON_DELIVERY => 'primary',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}