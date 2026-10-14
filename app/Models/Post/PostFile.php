<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'link',
        'created_by',
        'updated_by',
    ];
}
