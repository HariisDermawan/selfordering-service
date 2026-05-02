<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->product->with(['category', 'variants']);

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('name')->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->product->with(['category', 'variants', 'modifiers', 'recipes.material'])
            ->findOrFail($id);
    }

    public function findBySku(string $sku)
    {
        return $this->product->where('sku', $sku)->first();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['uuid'] = (string) Str::uuid();
            $data['sku'] = $data['sku'] ?? 'PRD-' . strtoupper(uniqid());
            
            return $this->product->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $product = $this->findById($id);
            $product->update($data);
            return $product;
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $product = $this->findById($id);
            return $product->delete();
        });
    }

    public function updateStock(int $id, int $quantity)
    {
        $product = $this->findById($id);
        $product->increment('stock', $quantity);
        return $product;
    }

    public function checkAvailability(int $id, int $quantity)
    {
        $product = $this->findById($id);
        return $product->stock >= $quantity;
    }
}