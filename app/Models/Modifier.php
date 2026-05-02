<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\ModifierType;

class Modifier extends Model
{
    protected $fillable = [
        'name', 'type', 'is_required', 'min_selection', 'max_selection', 'is_active'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'min_selection' => 'integer',
        'max_selection' => 'integer',
        'type' => ModifierType::class,
    ];

    public function options(): HasMany
    {
        return $this->hasMany(ModifierOption::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_modifiers');
    }
}