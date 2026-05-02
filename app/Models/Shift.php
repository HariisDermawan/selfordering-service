<?php

namespace App\Models;

use App\Enums\ShiftStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shift extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'opened_at', 'closed_at', 'opening_balance',
        'closing_balance', 'cash_sales', 'non_cash_sales', 'total_sales',
        'status', 'notes'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'cash_sales' => 'decimal:2',
        'non_cash_sales' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'status' => ShiftStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cashDrawerLogs(): HasMany
    {
        return $this->hasMany(CashDrawerLog::class);
    }

    public function isOpen(): bool
    {
        return $this->status === ShiftStatus::OPEN;
    }
}