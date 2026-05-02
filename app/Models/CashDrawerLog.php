<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\CashMovementType;

class CashDrawerLog extends Model
{
    protected $fillable = [
        'shift_id', 'type', 'amount', 'reason', 'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'type' => CashMovementType::class,
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}