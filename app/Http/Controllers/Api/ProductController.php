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
}
