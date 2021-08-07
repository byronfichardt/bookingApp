<?php

namespace App\V1\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'start' => $this->start_time,
            'end' => $this->end_time,
            'products' => ProductResource::collection($this->products),
            'user' => new UserResource($this->user),
            'path' => $this->path ? Storage::url($this->path) : null
        ];
    }
}
