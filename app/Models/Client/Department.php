<?php

namespace App\Models\Client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'short_name',
        'full_name',
        'active',
        'is_company',
        'created_by',
        'updated_by',
        'id',
    ];

    public function department()
    {
        return $this->hasMany(Department::class, 'parent_id', 'id')->where('type_id', 2)->orWhere('type_id', 1);
    }

    public function users()
    {
        return $this->hasManyThrough(Client::class, User::class, 'department_id', 'id', 'id');
    }
}
