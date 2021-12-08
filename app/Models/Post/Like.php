<?php

namespace App\Models\Post;

use App\Models\Client\Client;
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
        return $this->hasOne(Client::class, 'id', 'user_id');
    }
}
