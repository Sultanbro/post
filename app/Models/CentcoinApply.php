<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentcoinApply extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'user_id',
        'total',
        'quantity',
        'status',
        'created_by',
        'updated_by',
        ];

    public function applyUser()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }
}
