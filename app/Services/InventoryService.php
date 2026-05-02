<?php

namespace App\Services;

use App\Models\Material;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryService
{
    protected $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    public function getAllMaterials(array $filters = [])
    {
        $query = $this->material->query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['low_stock'])) {
            $query->whereRaw('stock <= min_stock');
        }

        return $query->orderBy('name')->paginate($filters['per_page'] ?? 15);
    }

    public function getLowStock()
    {
        return $this->material->whereRaw('stock <= min_stock')->get();
    }

    public function addStock(int $materialId, float $quantity, string $notes = null)
    {
        return DB::transaction(function () use ($materialId, $quantity, $notes) {
            $material = $this->material->findOrFail($materialId);
            $beforeStock = $material->stock;

            $material->increment('stock', $quantity);

            StockMovement::create([
                'material_id' => $material->id,
                'type' => 'in',
                'quantity' => $quantity,
                'before_stock' => $beforeStock,
                'after_stock' => $material->stock,
                'notes' => $notes ?? 'Stock addition',
                'created_by' => auth()->id()
            ]);

            return $material;
        });
    }

    public function deductStock(int $materialId, float $quantity, string $referenceType = null, int $referenceId = null)
    {
        return DB::transaction(function () use ($materialId, $quantity, $referenceType, $referenceId) {
            $material = $this->material->findOrFail($materialId);

            if ($material->stock < $quantity) {
                throw new \Exception("Insufficient stock for {$material->name}. Available: {$material->stock}, Required: {$quantity}");
            }

            $beforeStock = $material->stock;
            $material->decrement('stock', $quantity);

            StockMovement::create([
                'material_id' => $material->id,
                'type' => 'out',
                'quantity' => $quantity,
                'before_stock' => $beforeStock,
                'after_stock' => $material->stock,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'notes' => 'Stock deduction',
                'created_by' => auth()->id()
            ]);

            return $material;
        });
    }

    public function getStockMovements(int $materialId, array $filters = [])
    {
        $query = StockMovement::where('material_id', $materialId);

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 15);
    }

    public function createMaterial(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['uuid'] = (string) Str::uuid();
            $data['sku'] = $data['sku'] ?? 'MTL-' . strtoupper(uniqid());

            return $this->material->create($data);
        });
    }

    public function updateMaterial(int $id, array $data)
    {
        $material = $this->material->findOrFail($id);
        $material->update($data);
        return $material;
    }
}
