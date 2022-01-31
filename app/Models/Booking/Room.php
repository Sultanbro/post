<?php

namespace App\Models\Booking;

use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'capacity',
        'address',
        'floor',
        'cabinet',
        'description',
        'created_by',
        'updated_by',
    ];

    public function roomCompany()
    {
        return $this->hasOne(Client::class,'id','company_id');
    }
}
