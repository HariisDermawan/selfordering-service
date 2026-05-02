<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\TableStatus;

class Table extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'table_number', 'capacity', 'status', 'qr_code'
    ];

    protected $casts = [
        'status' => TableStatus::class,
        'capacity' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === TableStatus::AVAILABLE;
    }

    public function occupy(): void
    {
        $this->update(['status' => TableStatus::OCCUPIED]);
    }

    public function free(): void
    {
        $this->update(['status' => TableStatus::AVAILABLE]);
    }
}
