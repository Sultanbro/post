<?php

namespace App\Models\Client\UserStory;

use App\Models\Reference\Duty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffUser extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function duty()
    {
        return $this->hasOne(Duty::class, 'id', 'duty_id');
    }
}
