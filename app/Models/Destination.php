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
}
