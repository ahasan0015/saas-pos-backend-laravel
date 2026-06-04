<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Due to the BelongsToTenant trait, Product::all()
        // is automatically filtered by the tenant_id of the authenticated user.
        $products = Product::all();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
    public function store(Request $request)
    {
        // 1. Validate incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        // 2. Generate a unique SKU automatically
        $validated['sku'] = 'PROD-' . strtoupper(substr(uniqid(), -5));
        // Alternatively, for a more secure SKU:
        // $validated['sku'] = 'PROD-' . bin2hex(random_bytes(3));

        // 3. Store the product in the database
        // (tenant_id will be automatically assigned via the BelongsToTenant trait)
        $product = Product::create($validated);

        // 4. Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }
}
