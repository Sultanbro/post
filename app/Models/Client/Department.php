<?php

namespace App\Models\Client;

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
}
