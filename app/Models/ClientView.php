<?php

namespace App\Models;

use App\Models\Client\Department;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClientView extends Model
{
    use HasFactory;

    protected $table = 'views_client_info';

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'client_id', 'client_id');
    }

    public function scopeBirthDayBetween ($query, Carbon $from)
    {
//        if ($from->format('m-d') == '12-22') {

        $query->whereRaw("date_part('doy', birthday) BETWEEN date_part('doy', CURRENT_DATE) AND date_part('doy', CURRENT_DATE + INTERVAL '10 days')");
        $query->orderByRaw("date_part('doy', birthday)");
//        } else {
//            $query->whereRaw("date_part('doy', birthday) BETWEEN date_part('doy', CURRENT_DATE + INTERVAL '10 days') AND date_part('doy', CURRENT_DATE)");
//        }
    }

    public function scopeCompany($query, $company_id)
    {
        if (is_null($company_id)) {
            $company_id = Auth::user()->company_id;
            return $query->whereRaw("company_id =  $company_id");
        }
        if ($company_id == 1) {
            return $query;
        }
        return $query->whereRaw("company_id =  $company_id");
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
