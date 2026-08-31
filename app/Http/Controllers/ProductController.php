<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min(
            $request->integer('per_page', 10),
            100
        );
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = [
            'id',
            'name',
            'price',
            'stock',
            'created_at',
        ];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $products = Product::query()
            ->search($request->get('search'))
            ->minPrice($request->get('min_price'))
            ->maxPrice($request->get('max_price'))
            ->inStock($request->boolean('in_stock'))
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {

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
