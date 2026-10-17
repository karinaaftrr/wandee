<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';

    protected $fillable = [
        'title',
        'location',
        'category',
        'image',
        'trip_date',
        'price',
        'quota',
        'rating',
        'description',
        'itinerary',
        'facilities',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->orderByDesc('created_at');
    }
}
