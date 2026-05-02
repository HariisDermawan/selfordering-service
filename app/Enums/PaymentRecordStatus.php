<?php

namespace App\Enums;

enum PaymentRecordStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::SUCCESS => 'Berhasil',
            self::FAILED => 'Gagal',
            self::REFUNDED => 'Dikembalikan',
        };
    }
}