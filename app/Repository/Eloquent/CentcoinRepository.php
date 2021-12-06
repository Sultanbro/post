<?php

namespace App\Repository\Eloquent;

use App\Http\Resources\Centcoin\CentcoinResource;
use App\Models\Centcoin;
use App\Repository\CentcoinRepositoryInterface;
use Illuminate\Http\Request;

class CentcoinRepository extends BaseRepository implements CentcoinRepositoryInterface
{

    /**
     * CentcoinRepository constructor.
     * @param Centcoin $model
     */
    public function __construct(Centcoin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


}
