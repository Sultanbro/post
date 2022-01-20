<?php


namespace App\Repository\Client;


use App\Models\Client\Client;
use App\Repository\Eloquent\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * ClientRepository constructor.
     * @param Client $model
     */
    public function __construct(Client $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }


    /**
     * @inheritDoc
     */
    public function getComingBDay($addDay)
    {
//        return DB::table('clients')->whereBetween("date_part('doy', birthday)", ["date_part('doy', CURRENT_DATE)", "date_part('doy', CURRENT_DATE + INTERVAL '10 days')"])
//            ->orWhereBetween("date_part('doy', birthday)", ["date_part('doy', CURRENT_DATE + INTERVAL '10 days')", "date_part('doy', CURRENT_DATE))"])->get();
//        return DB::raw("select id, full_name, birthday, company_id  from clients
//                where date_part('doy', birthday) between
//                date_part('doy', CURRENT_DATE) and
//                date_part('doy', CURRENT_DATE + INTERVAL '10 days') or
//                date_part('doy', birthday) between
//                date_part('doy', CURRENT_DATE + INTERVAL '10 days') and
//                date_part('doy', CURRENT_DATE))")->getValue();
//        return $this->model->where("date_part('doy', birthday) BETWEEN date_part('doy', CURRENT_DATE) AND date_part('doy', CURRENT_DATE + INTERVAL '10 days')")->get();
//        return $this->model->where(['between', "date_pa   rt('doy', birthday)", ["date_part('doy', CURRENT_DATE)", "date_part('doy', CURRENT_DATE + INTERVAL '10 days')"]])->get();
//        return $this->model->whereBetween('date_part('doy', birthday)', [date_part('doy', 'CURRENT_DATE'), date_part('doy', 'CURRENT_DATE + INTERVAL '10 days'')])->get()
//        return $this->model->whereDayBetween('birthday', [Carbon::today()->format('d'), Carbon::today()->addDays(10)->format('d')])->whereBetween('birthday', [Carbon::today()->format('m'), Carbon::today()->addDays(10)->format('m')])->get();
//        return $this->model->where(['between', 'birthday', [Carbon::today()->format('d'), Carbon::today()->addDays(10)->format('m')]])->get();
//        return $this->model->whereBetweenDayAndMonth('birthday', [Carbon::today()->format('m-d'), Carbon::today()->addDays(10)->format('m-d')])->get();
//        return $this->model->whereDay()
//        return $this->model->whereBetween(new Expression("to_char(birthday, 'MM-DD')"), [Carbon::today()->format('m-d'), Carbon::today()->addDays(10)->format('m-d')])->get();
        return $this->model->birthDayBetween(Carbon::now(), Carbon::now()->addDays($addDay))->where('type_id', 1)->get();
    }
}
