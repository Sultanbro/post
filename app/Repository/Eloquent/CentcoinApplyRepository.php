<?php

namespace App\Repository\Eloquent;

use App\Models\CentcoinApply;
use App\Repository\CentcoinApplyRepositoryInterface;

class CentcoinApplyRepository extends BaseRepository implements CentcoinApplyRepositoryInterface
{
    /**
     * CentcoinApplyRepository constructor.
     * @param CentcoinApply $model
     */
    public function __construct(CentcoinApply $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
