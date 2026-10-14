<?php


namespace App\Repository\Reference\City;


use App\Models\Reference\City;
use App\Repository\Eloquent\BaseRepository;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param City $model
     */
    public function __construct(City $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstForeignCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
