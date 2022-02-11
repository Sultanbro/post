<?php

namespace App\Repository\Centcoin;

use App\Models\Centcoin\CentcoinApply;
use App\Repository\Eloquent\BaseRepository;

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
