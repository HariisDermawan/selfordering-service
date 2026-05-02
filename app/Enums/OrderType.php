<?php

namespace App\Enums;

enum OrderType: string
{
    case DINE_IN = 'dine_in';
    case TAKEAWAY = 'takeaway';
    case DELIVERY = 'delivery';
    case KIOSK = 'kiosk';
    case CASHIER = 'cashier';

    public function label(): string
    {
        return match($this) {
            self::DINE_IN => 'Makan di Tempat',
            self::TAKEAWAY => 'Bungkus',
            self::DELIVERY => 'Delivery',
            self::KIOSK => 'Kiosk',
            self::CASHIER => 'Kasir',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::DINE_IN => 'fa-utensils',
            self::TAKEAWAY => 'fa-box',
            self::DELIVERY => 'fa-motorcycle',
            self::KIOSK => 'fa-desktop',
            self::CASHIER => 'fa-cash-register',
        };
    }
}