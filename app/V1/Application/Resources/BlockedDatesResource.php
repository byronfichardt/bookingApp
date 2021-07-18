<?php

namespace App\V1\Application\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockedDatesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->blocked_date,
            'times' => $this->times
        ];
    }
}
