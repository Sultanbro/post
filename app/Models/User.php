<?php

namespace App\Models;

use App\Models\Client\Client;
use App\Models\Client\ClientContact;
use App\Models\Client\Department;
use App\Models\Client\Eorder;
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

    public function userDepartment()
    {
        return $this->hasOne(Department::class,'id', 'department_id');
    }

    public function clientContact()
    {
        return$this->hasOne(ClientContact::class, 'id', 'id');
    }

    public function eOrder()
    {
        return $this->hasMany(Eorder::class,'client_id', 'id');
    }
}
