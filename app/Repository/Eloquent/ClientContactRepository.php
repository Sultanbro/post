<?php


namespace App\Repository\Eloquent;


use App\Models\Users\ClientContact;
use App\Repository\ClientContactRepositoryInterface;

class ClientContactRepository extends BaseRepository implements ClientContactRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param ClientContact $model
     */
    public function __construct(ClientContact $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
