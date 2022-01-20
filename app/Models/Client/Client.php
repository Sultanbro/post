<?php

namespace App\Models\Client;

use App\Models\Avatar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'parent_name',
        'short_name',
        'full_name',
        'iin',
        'sex',
        'resident_bool',
        'juridical_bool',
        'address',
        'type_id',
        'birthday',
        'updated_by',
        'created_by',
        'foreign_id',
        'company_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id', 'id');
    }

    protected function avatar()
    {
        return $this->hasOne(Avatar::class, 'user_id', 'id');
    }

    public function scopeBirthDayBetween ($query, Carbon $from, Carbon $till)
    {
        $fromMonthDay = $from->format('m-d');
        $tillMonthDay = $till->format('m-d');
        if ($fromMonthDay <= $tillMonthDay) {

            $query->whereRaw("DATE_FORMAT(birthday, '%mm-%dd') BETWEEN '{$fromMonthDay}' AND '{$tillMonthDay}'");
        } else {

            $query->where(function ($query) use ($fromMonthDay, $tillMonthDay) {
                $query->whereRaw("DATE_FORMAT(birthday, '%mm-%dd') BETWEEN '{$fromMonthDay}' AND '12-31'")
                    ->orWhereRaw("DATE_FORMAT(birthday, '%mm-%dd') BETWEEN '01-01' AND '{$tillMonthDay}'");
            });
        }
    }
}
