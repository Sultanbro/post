<?php

namespace App\Models;

use App\Models\Client\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    protected $guarded = [''];
    use AuthenticatableTrait;
    use HasFactory;

    public function token()
    {
        return $this->hasOne(UserToken::class);
    }

    public function clientInfo()
    {
        return $this->hasOne(Client::class, 'id', 'id');
    }
}
