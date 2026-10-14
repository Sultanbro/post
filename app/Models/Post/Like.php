<?php

namespace App\Models\Post;

use App\Models\Client\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'type',
        'user_id',
    ];

    public function likeUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
