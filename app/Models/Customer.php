<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    protected $fillable = [
        'uuid', 'name', 'email', 'phone', 'address', 'total_points'
    ];

    protected $casts = [
        'total_points' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}