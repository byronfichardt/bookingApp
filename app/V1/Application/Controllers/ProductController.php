<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\Product;
use App\Http\Controllers\Controller;
use App\V1\Application\Resources\ProductResource;
use App\V1\Domain\dtos\ProductDto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : null;

        $products = Product::with('prices')->orderBy('sort_order')->get();

        $products = $products->map(function (Product $product) use($date) {
            return new ProductDto($product, $product->getPrice($date));
        });

        return ProductResource::collection($products);
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
            'description' => $request->description,
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
        $product->description = $request->description;
        $product->price = $request->price;
        $product->minutes = $request->minutes;
        $product->display_quantity = $request->display_quantity;
        $product->sort_order = $request->sort_order;
        $product->save();

        return 'success';
    }
}
