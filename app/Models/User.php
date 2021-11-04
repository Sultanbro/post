<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [''];
    use Authenticatable;
    use HasFactory;

    public function token()
    {
        return $this->hasOne(UserToken::class);
    }
}
