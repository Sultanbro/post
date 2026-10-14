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
        'company_id',
        'foreign_id',
        'id',
    ];

    public function department()
    {
        return $this->hasMany(Department::class, 'parent_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id')->whereIn('type_id', [3,4]);
    }
}
