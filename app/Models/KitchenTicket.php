<?php

namespace App\Models;

use App\Enums\KitchenStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KitchenTicket extends Model
{
    protected $fillable = [
        'uuid', 'order_id', 'ticket_number', 'status',
        'pending_at', 'cooking_at', 'ready_at', 'served_at'
    ];

    protected $casts = [
        'status' => KitchenStatus::class,
        'pending_at' => 'datetime',
        'cooking_at' => 'datetime',
        'ready_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->ticket_number = 'KT-' . date('Ymd') . '-' . strtoupper(uniqid());
            $model->pending_at = now();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(KitchenTicketItem::class);
    }
}