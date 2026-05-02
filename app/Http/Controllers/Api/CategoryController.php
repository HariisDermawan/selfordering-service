<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getAll($request->all());
        
        return CategoryResource::collection($categories);
    }

    public function tree()
    {
        $categories = $this->categoryService->getTree();
        
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load('products'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category = $this->categoryService->update($category->id, $request->validated());
        
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category->id);
        
        return $this->successResponse(null, 'Category deleted successfully');
    }
}