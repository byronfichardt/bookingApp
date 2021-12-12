<?php

namespace App\V1\Application\Resources;

use App\V1\Application\Models\Product;
use App\V1\Domain\dtos\ProductDto;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        $products = $this->products->map(function (Product $product) {
            return new ProductDto($product, $product->getPrice());
        });

        return [
            'id' => $this->id,
            'note' => $this->note,
            'start' => $this->start_time->toDateTimeString(),
            'end' => $this->end_time->toDateTimeString(),
            'products' => ProductResource::collection($products),
            'user' => new UserResource($this->user),
            'path' => $this->path ? Storage::url($this->path) : null
        ];
    }
}
