<?php

namespace App\V1\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'minutes' => $this->minutes,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
