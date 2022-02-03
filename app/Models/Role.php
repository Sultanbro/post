<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'company_id'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

}
