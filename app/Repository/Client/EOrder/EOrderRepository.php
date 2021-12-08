<?php


namespace App\Repository\Client\EOrder;

use App\Models\Client\Eorder;
use App\Repository\Eloquent\BaseRepository;

class EOrderRepository extends BaseRepository implements EOrderRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param EOrder $model
     */
    public function __construct(EOrder $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function firstForeignIdCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
