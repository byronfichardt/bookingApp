<?php

namespace App\V1\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getProduct()->id,
            'name' => $this->getProduct()->name,
            'sort_order' => $this->getProduct()->sort_order,
            'description' => $this->getProduct()->description,
            'display_quantity' => $this->getProduct()->display_quantity,
            'price' => $this->getProductPrice()->price ?? $this->getProduct()->price,
            'minutes' => $this->getProduct()->minutes,
            'quantity' => property_exists($this->getProduct(),'pivot') ? $this->getProduct()->pivot->quantity : null,
        ];
    }
}
