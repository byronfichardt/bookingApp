<?php

namespace App\V1\Application\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'minutes' => 'integer'
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function getPrice(Carbon $date = null): ?ProductPrice
    {
        $checkDate = $date ?? now();

        return $this->prices
            ->where('start_date', '<', $checkDate)
            ->where('end_date', '>', $checkDate)
            ->first();
    }
}
