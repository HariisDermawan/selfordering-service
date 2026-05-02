<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case READY = 'ready';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::READY => 'Ready',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::READY => 'success',
            self::COMPLETED => 'primary',
            self::CANCELLED => 'danger',
            self::REFUNDED => 'secondary',
        };
    }

    public function canEdit(): bool
    {
        return in_array($this, [self::PENDING, self::PROCESSING]);
    }

    public function canCancel(): bool
    {
        return in_array($this, [self::PENDING, self::PROCESSING]);
    }
}