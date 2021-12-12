<?php

namespace App\V1\Application\Controllers;

use App\Http\Controllers\Controller;
use App\V1\Application\Models\productPrice;
use App\V1\Application\Resources\ProductPriceResource;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    public function index(Request $request)
    {
        $productPrices = ProductPrice::where('product_id', $request->product_id)->get();

        return ProductPriceResource::collection($productPrices);
    }

    public function store(Request $request)
    {
        $product = $request->product;
        $prices = $request->prices;

        ProductPrice::where('product_id', $product['id'])->delete();
        foreach ($prices as $price) {
            ProductPrice::create([
                'product_id' => $product['id'],
                'price' => $price['price'],
                'start_date' => $price['start_date'],
                'end_date' => $price['end_date'],
            ]);
        }

        return 'success';
    }
}
