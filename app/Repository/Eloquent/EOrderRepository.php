<?php


namespace App\Repository\Eloquent;

use App\Models\Users\Eorder;
use App\Repository\EOrderRepositoryInterface;

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
}
