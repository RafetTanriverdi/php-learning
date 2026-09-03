<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(ProductFilterRequest $request)
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 10;

        $direction = $validated['direction'] ?? 'asc';
        $sort = $validated['sort'] ?? 'id';
        $products = Product::query()
            ->with('category')
            ->search($validated['search'] ?? null)
            ->minPrice($validated['min_price'] ?? null)
            ->maxPrice($validated['max_price'] ?? null)
            ->inStock($request->boolean('in_stock') ?? false)
            ->category($validated['category_id'] ?? null)
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return ProductResource::collection($products);

    }

    public function show(Product $product)
    {
        $product->loadMissing('category');

        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {

        $this->authorize('create', Product::class);

        $product = Product::create(
            $request->validated()
        );

        return $this->successResponse(
            data: new ProductResource($product->load('category')),
            message: 'Ürün oluşturuldu.',
            status: 201
        );
    }

    public function update(UpdateProductRequest $request, Product $product)
    {

        $this->authorize('update', $product);
        $product->update($request->validated());

        return $this->successResponse(
            data: new ProductResource($product->load('category')),
            message: 'Ürün güncellendi.'
        );

    }

    public function destroy(Product $product)
    {

        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message' => ' Product Deleted',
        ]);
    }
}
