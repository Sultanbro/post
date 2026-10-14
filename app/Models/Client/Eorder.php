<?php

namespace App\Models\Client;

use App\Models\Client\UserStory\StaffUser;
use App\Models\Reference\Dicti;
use App\Models\Reference\Duty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eorder extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function staff()
    {
        return $this->hasOne(StaffUser::class, 'id', 'staff_new_id');
    }

    public function type()
    {
        return $this->hasOne(Dicti::class, 'id', 'type_id');
    }
}
