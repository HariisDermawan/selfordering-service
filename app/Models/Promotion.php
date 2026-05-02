<?php

namespace App\Models;

use App\Enums\PromotionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Promotion extends Model
{
    protected $fillable = [
        'uuid', 'name', 'code', 'type', 'discount_value', 'min_purchase',
        'max_discount', 'start_date', 'end_date', 'usage_limit', 'used_count', 'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'type' => PromotionType::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    public function buyXGetYRule(): HasMany
    {
        return $this->hasMany(BuyXGetYRule::class);
    }

    public function orderPromotions(): HasMany
    {
        return $this->hasMany(OrderPromotion::class);
    }

    public function isValid(): bool
    {
        $now = now();
        return $this->is_active && 
               $now >= $this->start_date && 
               $now <= $this->end_date &&
               (!$this->usage_limit || $this->used_count < $this->usage_limit);
    }
}