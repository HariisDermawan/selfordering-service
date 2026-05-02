<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Material extends Model
{
    use SoftDeletes;

    protected $table = 'materials';
    
    protected $fillable = [
        'uuid', 'name', 'sku', 'unit', 'stock', 'min_stock', 'unit_price'
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            if (!$model->sku) {
                $model->sku = 'MTL-' . strtoupper(uniqid());
            }
        });
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function addStock(float $quantity, string $notes = null, $userId = null)
    {
        $beforeStock = $this->stock;
        $this->increment('stock', $quantity);
        
        StockMovement::create([
            'material_id' => $this->id,
            'type' => 'in',
            'quantity' => $quantity,
            'before_stock' => $beforeStock,
            'after_stock' => $this->stock,
            'notes' => $notes ?? 'Stock addition',
            'created_by' => $userId ?? auth()->id()
        ]);
        
        return $this;
    }

    public function deductStock(float $quantity, string $referenceType = null, int $referenceId = null, $userId = null)
    {
        if ($this->stock < $quantity) {
            throw new \Exception("Insufficient stock for {$this->name}");
        }
        
        $beforeStock = $this->stock;
        $this->decrement('stock', $quantity);
        
        StockMovement::create([
            'material_id' => $this->id,
            'type' => 'out',
            'quantity' => $quantity,
            'before_stock' => $beforeStock,
            'after_stock' => $this->stock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => 'Stock deduction',
            'created_by' => $userId ?? auth()->id()
        ]);
        
        return $this;
    }
}