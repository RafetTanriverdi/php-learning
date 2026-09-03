<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $categories = Category::query()
            ->orderBy('id')
            ->paginate(10);

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        $category->load('products');

        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        $category = Category::create(
            $request->validated()
        );

        return $this->successResponse(
            data: new CategoryResource($category),
            message: 'Kategori oluşturuldu',
            status: 201
        );

    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ) {
        $this->authorize('delete', $category);
        $category->update(
            $request->validated()
        );

        return $this->successResponse(
            data: new CategoryResource($category),
            message: 'Kategori Güncellendi',
            status: 201
        );

    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted',
        ]);
    }
}
