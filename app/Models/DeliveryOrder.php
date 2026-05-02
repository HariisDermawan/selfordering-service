<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'order_id', 'delivery_platform_id', 'driver_id', 'platform_order_id',
        'delivery_address', 'customer_name', 'customer_phone', 'delivery_fee',
        'status', 'estimated_delivery_time', 'delivered_at'
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'status' => DeliveryStatus::class,
        'estimated_delivery_time' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(DeliveryPlatform::class, 'delivery_platform_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(DeliveryStatusLog::class);
    }
}