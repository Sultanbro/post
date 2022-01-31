<?php

namespace App\Models;

use App\Models\Client\Client;
use App\Models\Client\ClientContact;
use App\Models\Client\Department;
use App\Models\Client\Eorder;
use App\Models\Client\UserStory\CareerUser;
use App\Models\Client\UserStory\Employee;
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
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function userDepartment()
    {
        return $this->hasOne(Department::class,'id', 'department_id');
    }

    public function clientContact()
    {
        return$this->hasOne(ClientContact::class, 'client_id', 'id');
    }

    public function eOrder()
    {
        return $this->hasMany(Eorder::class,'client_id', 'id');
    }

    public function employees()
    {
        return $this->hasOne(Employee::class, 'id', 'id');
    }

    public function career()
    {
        return $this->hasMany(Eorder::class, 'client_id', 'id')->where('type_id', 12)->orWhere('type_id', 14);
    }

    public function vacation()
    {
        return $this->hasMany(Eorder::class, 'client_id', 'id')->where('type_id', 13);
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function company()
    {
        return $this->hasOne(Department::class, 'id', 'company_id');
    }
}
