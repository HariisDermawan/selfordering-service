<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $products = $this->productService->getAll($request->all());
        
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'variants', 'modifiers']));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->productService->update($product->id, $request->validated());
        
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product->id);
        
        return $this->successResponse(null, 'Product deleted successfully');
    }

    public function getByCategory($categoryId)
    {
        $products = $this->productService->getAll(['category_id' => $categoryId]);
        
        return ProductResource::collection($products);
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        
        $product = $this->productService->updateStock($product->id, $request->stock);
        
        return $this->successResponse($product, 'Stock updated');
    }
}