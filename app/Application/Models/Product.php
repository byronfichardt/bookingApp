<?php

namespace App\Application\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'minutes' => 'integer'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class);
    }
}
