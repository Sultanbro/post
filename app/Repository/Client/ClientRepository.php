<?php


namespace App\Repository\Client;


use App\Models\Client\Client;
use App\Repository\Eloquent\BaseRepository;
use Carbon\Carbon;

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
        return $this->model->birthDayBetween(Carbon::now(), Carbon::now()->addDays($addDay))->where('type_id', 1)->get();
    }
}
