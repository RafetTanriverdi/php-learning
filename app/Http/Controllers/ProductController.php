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
        $products = Product::paginate($perPage);

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
