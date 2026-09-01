<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
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
            ->inStock($request->boolean('in_stock'))
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

        $product = Product::create(
            $request->validated()
        );

        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return response()->json($product);
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return response()->json([
            'message' => ' Product Deleted',
        ]);
    }
}
