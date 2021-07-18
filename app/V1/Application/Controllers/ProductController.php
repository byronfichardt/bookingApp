<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::orderBy('sort_order')->get()->toJson();
    }

    public function fetch(Request $request, string $date)
    {
        return Product::where('created_at', $date)->orderBy('name')->get()->toJson();
    }

    public function store(Request $request)
    {
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'minutes' => $request->minutes,
            'display_quantity' => $request->display_quantity ?? false,
            'sort_order' => $request->sort_order,
        ]);
        return 'success';
    }

    public function delete(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return 'success';
    }
    public function edit(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->minutes = $request->minutes;
        $product->display_quantity = $request->display_quantity;
        $product->sort_order = $request->sort_order;
        $product->save();

        return 'success';
    }
}
