<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'destination_id',
        'total_people',
        'total_price',
        'payment_status',
        'trip_status',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
