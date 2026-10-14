<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailDomain extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'active', 'created_by', 'updated_by'];
}
