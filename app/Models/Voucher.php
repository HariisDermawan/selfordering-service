<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\VoucherType;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'expires_at', 'min_purchase', 'usage_limit', 'used_count'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'type' => VoucherType::class,
    ];

    public function orderPromotions(): HasMany
    {
        return $this->hasMany(OrderPromotion::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && 
               (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }
}