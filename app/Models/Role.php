<?php

namespace App\Models;

use App\Models\Client\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'company_id'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function company()
    {
        return $this->hasOne(Department::class, 'id','company_id');
    }

}
