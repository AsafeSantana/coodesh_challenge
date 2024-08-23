<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);
        return response()->json($products);
    }

    public function show($code)
{
    $product = Product::where('code', $code)->first();

    if ($product) {
        return response()->json($product);
    } else {
        return response()->json(['message' => 'Product not found'], 404);
    }
}

public function update(Request $request, $code)
{
    $request->validate([
        'status' => 'required|in:draft,trash,published',
        'imported_t' => 'nullable|date_format:Y-m-d H:i:s',
        'url' => 'nullable|url',
        'creator' => 'nullable|string',
        'created_t' => 'nullable|integer',
        'last_modified_t' => 'nullable|integer',
        'product_name' => 'nullable|string',
        'quantity' => 'nullable|string',
        'brands' => 'nullable|string',
        'categories' => 'nullable|string',
        'labels' => 'nullable|string',
        'cities' => 'nullable|string',
        'purchase_places' => 'nullable|string',
        'stores' => 'nullable|string',
        'ingredients_text' => 'nullable|string',
        'traces' => 'nullable|string',
        'serving_size' => 'nullable|string',
        'serving_quantity' => 'nullable|numeric',
        'nutriscore_score' => 'nullable|integer',
        'nutriscore_grade' => 'nullable|string',
        'main_category' => 'nullable|string',
        'image_url' => 'nullable|url',
    ]);

    // Tenta encontrar o produto pelo código
    $product = Product::where('code', $code)->first();

    if ($product) {
        // Atualiza os campos permitidos, mas mantém os antigos se não forem fornecidos
        $product->fill($request->except(['created_t', 'last_modified_t', 'imported_t']));

        // Atualiza timestamps
        if ($request->has('created_t')) {
            $product->created_t = Carbon::createFromTimestamp($request->input('created_t'))->format('Y-m-d H:i:s');
        }

        if ($request->has('last_modified_t')) {
            $product->last_modified_t = Carbon::createFromTimestamp($request->input('last_modified_t'))->format('Y-m-d H:i:s');
        }

        if ($request->has('imported_t')) {
            $product->imported_t = Carbon::parse($request->input('imported_t'))->format('Y-m-d H:i:s');
        }

        $product->save();

        return response()->json($product, 200);
    } else {
        // Produto não encontrado
        return response()->json(['message' => 'Product not found'], 404);
    }
}




    public function destroy($code)
    {
        $product = Product::where('code', $code)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(['status' => 'trash']);
        return response()->json(['message' => 'Product moved to trash']);
    }
}
