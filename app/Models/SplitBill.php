<?php

namespace App\Models;

use App\Enums\SplitBillStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SplitBill extends Model
{
    protected $fillable = [
        'order_id', 'split_number', 'amount', 'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => SplitBillStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->split_number = (string) Str::uuid();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SplitBillItem::class);
    }
}