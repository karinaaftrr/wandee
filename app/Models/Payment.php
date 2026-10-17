<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'payment_method',
        'payment_amount',
        'unique_code',
        'payment_proof',
        'payment_status',
        'rejection_reason',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
