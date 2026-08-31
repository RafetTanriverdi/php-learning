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

        $perPage = $request->integer('per_page', 10);

        $perPage = min($perPage, 100);

        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('in_stock')) {
            if ($request->boolean('in_stock')) {
                $query->where('stock', '>', 0);
            }
        }
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = [
            'id', 'name', 'price',
            'stock', 'created_at',
        ];

        if (! in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $products = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return ProductResource::collection(
            $products
        );
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
