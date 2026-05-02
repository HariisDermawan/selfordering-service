<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaxType;

class Tax extends Model
{
    protected $fillable = [
        'name', 'code', 'rate', 'type', 'is_active'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'type' => TaxType::class,
    ];
}