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

    public function scopeBirthDayBetween ($query, Carbon $from, $till)
    {
//        $fromMonthDay = $from->format('m-d');
//        $tillMonthDay = $till->format('m-d');
        if ($from->format('m-d') == '12-25') {

            $query->whereRaw("date_part('doy', birthday) BETWEEN date_part('doy', CURRENT_DATE) AND date_part('doy', CURRENT_DATE + INTERVAL '$till days')");
        } else {
            $query->whereRaw("date_part('doy', birthday) BETWEEN date_part('doy', CURRENT_DATE + INTERVAL '$till days') AND date_part('doy', CURRENT_DATE)");
        }
//
//            $query->where(function ($query) use ($fromMonthDay, $tillMonthDay) {
//                $query->whereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN '{$fromMonthDay}' AND '12-31'")
//                    ->orWhereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN '01-01' AND '{$tillMonthDay}'");
//            });
//        }
    }
}
