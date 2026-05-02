<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\DriverStatus;

class Driver extends Model
{
    protected $fillable = [
        'name', 'phone', 'vehicle_number', 'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'status' => DriverStatus::class,
    ];

    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    public function setBusy(): void
    {
        $this->update(['is_available' => false]);
    }

    public function setAvailable(): void
    {
        $this->update(['is_available' => true]);
    }
}