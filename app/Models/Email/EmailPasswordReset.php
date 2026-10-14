<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailPasswordReset extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'status'];
}
