<?php


namespace App\Repository\Eloquent;


use App\Models\Dicti;
use App\Repository\DictiRepositoryInterface;

class DictiRepository extends BaseRepository implements DictiRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Dicti $model
     */
    public function __construct(Dicti $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $name
     * @param int $foreign_id
     * @return mixed
     */
    public function compareInNameAndParentId(string $name, int $foreign_id)
    {
        return $this->model->whereForeign_idAndFull_name($foreign_id, $name)->first();
    }


    /**
     * @param int $foreign_id
     * @param int $company_id
     * @return mixed
     */
    public function firstWhereForeignIdCompanyId(int $foreign_id, int $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
