<?php


namespace App\Repository\Client;


use App\Models\Client\Client;
use App\Repository\Eloquent\BaseRepository;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{
    /**
     * ClientRepository constructor.
     * @param Client $model
     */
    public function __construct(Client $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed
     */
    public function firstWhereForeignId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }


}
