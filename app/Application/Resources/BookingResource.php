<?php

namespace App\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'start' => $this->start_time,
            'end' => $this->end_time,
            'products' => ProductResource::collection($this->products),
            'user' => new UserResource($this->user),
        ];
    }
}
