<?php

namespace App\Models\Post;

use App\Models\Client\Client;
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
        return $this->hasOne(Like::class, 'parent_id', 'id')->where('type', 1)->whereUserId(Auth::id());
    }

    public function postComments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id')->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function commentsLimit()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id')->whereNull('parent_id')->take(3);
    }

    public function scopeCompany($query, $company_id)
    {
        if (!is_null($company_id)) {
            return $query->where('company_id', $company_id);
        }else{
            return $query->where('company_id', Auth::user()->company_id);
        }

        return $query;
    }

    public function scopeDate($query, $date)
    {
        if (!is_null($date)) {
            if ($date == 'old') {
                return $query;
            }
            if ($date == 'new') {
                return $query->latest();
            }
        }

        return $query;
    }

    public function scopeUserLiked($query, $params)
    {
        if (!is_null($params)) {
            $posts_id = Like::where('type', 1)->whereUserId(Auth::id())->select('parent_id')->get();
                return $query->whereIn('id', $posts_id);
        }

        return $query;
    }

}
