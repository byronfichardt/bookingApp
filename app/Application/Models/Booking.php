<?php

namespace App\Application\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected static function newFactory()
    {
        return BookingFactory::new();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withPivot('quantity');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
