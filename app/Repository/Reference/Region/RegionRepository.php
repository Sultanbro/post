<?php


namespace App\Repository\Reference\Region;


use App\Models\Reference\Region;
use App\Repository\Eloquent\BaseRepository;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Region $model
     */
    public function __construct(Region $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function firstByForeignId($id)
    {
        return $this->model->firstWhere('foreign_id', $id);
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstByForeignIdCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
