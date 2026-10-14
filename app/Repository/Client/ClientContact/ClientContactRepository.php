<?php


namespace App\Repository\Client\ClientContact;


use App\Models\Client\ClientContact;
use App\Repository\Eloquent\BaseRepository;

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

    /**
     * @inheritDoc
     */
    public function getByClientId($id)
    {
        return $this->model->firstWhere('client_id', $id);
    }
}
