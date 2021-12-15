<?php

namespace App\Models\Post;

use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = [
        'parent_id',
        'post_id',
        'content',
        'user_id',
        'created_by',
        'updated_by',
];
    use HasFactory;

    public function commentUser()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }

    public function countLike()
    {
        return $this->hasMany(Like::class, 'parent_id', 'id')->where('type', 2);
    }

    public function isLiked()
    {
        return $this->hasOne(Like::class, 'parent_id', 'id')->whereUser_idAndType(Auth::id(), 2);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function oneComment()
    {
        return $this->hasOne(Comment::class, 'parent_id', 'id');
    }

    public function liked()
    {
        return $this->hasOne(Like::class, 'parent_id', 'id')->whereType(2)->whereUserId(Auth::id());
    }
}
