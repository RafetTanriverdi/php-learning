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
        $user = $request->user();

        $alreadyExists = $user->favorites()
            ->where('products.id', $product->id)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'message' => 'Bu ürün zaten favorilerde.',
            ], 409);
        }

        $user->favorites()
            ->attach($product->id);

        return response()->json([
            'message' => 'Ürün favorilere eklendi.',
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
