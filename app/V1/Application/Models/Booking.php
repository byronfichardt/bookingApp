<?php

namespace App\V1\Application\Models;

use Database\Factories\V1\Application\Models\BookingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function newFactory(): BookingFactory
    {
        return BookingFactory::new();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function setActive(string $eventId)
    {
        $this->status = 'active';
        $this->event_id = $eventId;
        $this->save();
    }

    public function calculateMinutes(): int
    {
        return $this->products->map(function($product) {
            return $product->minutes * $product->pivot->quantity;
        })->sum();
    }
}
