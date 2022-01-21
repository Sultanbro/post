<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'role_id',
        'user_id',
    ];
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
