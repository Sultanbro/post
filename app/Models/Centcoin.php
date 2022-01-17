<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centcoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'description',
        'quantity',
        'total',
        'user_id',
        'created_by',
        'updated_by',
    ];

    public function cointUser()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }




}
