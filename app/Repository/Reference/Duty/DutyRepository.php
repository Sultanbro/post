<?php


namespace App\Repository\Reference\Duty;


use App\Models\Reference\Duty;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class DutyRepository extends BaseRepository implements DutyRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Duty $model
     */
    public function __construct(Duty $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function getByForeignIdCompanyId()
    {
//        return $this->model->whereForeign_idAnd
    }

}
