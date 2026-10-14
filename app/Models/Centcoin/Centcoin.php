<?php

namespace App\Models\Centcoin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centcoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'description',
        'quantity',
        'user_id',
        'total',
        'created_by',
        'updated_by',
    ];

    public function cointUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
