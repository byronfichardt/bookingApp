<?php

namespace App\V1\Application\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function hasTimes()
    {
        return $this->times;
    }

    public function times(): array
    {
        return explode(',', $this->times);
    }
}
