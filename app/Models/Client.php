<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'parent_name',
        'short_name',
        'full_name',
        'iin',
        'sex',
        'resident_bool',
        'juridical_bool',
        'address',
        'type_id',
        'birthday',
        'updated_by',
        'created_by',
        'foreign_id',
        'company_id',
    ];
}
