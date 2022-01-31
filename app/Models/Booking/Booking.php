<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_id',
        'begin',
        'end',
        'description',
        'created_by',
        'updated_by',
    ];

    public function bookingRoom()
    {
        return $this->hasOne(Room::class,'id','room_id');
    }
}
