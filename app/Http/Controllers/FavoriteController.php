<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()
            ->favoriteProducts()
            ->with('category')
            ->latest('favorites.created_at')
            ->paginate(20);

        return ProductResource::collection($products);

    }

    public function store(Request $request, Product $product)
    {
        $request->user()
            ->favoriteProducts()
            ->syncWithoutDetaching(['$product->id']);

        return response->json([
            'message' => 'Ürün Favorilere eklendi',
        ], 201);
    }

    public function destroy(Request $request, Product $product)
    {
        $request->user()
            ->favoriteProducts()
            ->detach($product->id);

        return response()->json([
            'message' => 'Ürün favorilerden Çıkartıldı',
        ]);
    }
}
