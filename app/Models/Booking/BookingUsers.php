<?php

namespace App\Models\Booking;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingUsers extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'booking_id',
    ];

    public function bookingUsers()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function bookingId()
    {
        return $this->hasOne(Booking::class,'id','booking_id');
    }
}
