<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
        'company_id',
        'group_id',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'created_by'
    ];

    public function postsUser()
    {
        return $this->hasOne(Client::class, 'id', 'user_id');
    }

    public function postFiles()
    {
        return $this->hasMany(PostFile::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class, 'parent_id', 'id')->where('type', 1);
    }

    public function liked()
    {
        return $this->hasOne(Like::class, 'parent_id', 'id')->where('type', 1)->where('user_id', Auth::id());
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

}
