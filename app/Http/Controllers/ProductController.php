<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{

    public function index()
    {
        return response()->json([
            Product::all()
        ]);
    }



    public function show(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    'message' => "Urun bulunamadi"
                ],
                404
            );
        }
        return response()->json($product);
    }

    public function  store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);
        return response()->json($product, 201);
    }
}
