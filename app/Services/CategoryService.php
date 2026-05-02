<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->category->with('products');

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('sort_order')->get();
    }

    public function getTree()
    {
        return $this->category->with('children')->whereNull('parent_id')->get();
    }

    public function findById(int $id)
    {
        return $this->category->with('products')->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->category->where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['uuid'] = (string) Str::uuid();
            $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
            
            return $this->category->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $category = $this->findById($id);
            
            if (isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }
            
            $category->update($data);
            return $category;
        });
    }

    public function delete(int $id)
    {
        $category = $this->findById($id);
        
        if ($category->products()->exists()) {
            throw new \Exception('Cannot delete category with products');
        }
        
        return $category->delete();
    }
}