<?php

namespace App\Repository\Centcoin;

use App\Models\Centcoin\Centcoin;
use App\Repository\Eloquent\BaseRepository;

class CentcoinRepository extends BaseRepository implements CentcoinRepositoryInterface
{

    /**
     * @param Centcoin $model
     */
    public function __construct(Centcoin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
