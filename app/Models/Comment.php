<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'parent_id',
        'content',
        'user_id',
        'created_by',
        'updated_by',
];
    use HasFactory;

    public function commentUser()
    {
        return $this->hasOne(Client::class);
    }

    public function countLike()
    {
        return $this->hasMany(Like::class, 'parent_id', 'id')->where('type', 2)->count();
    }
}
