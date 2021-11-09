<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
