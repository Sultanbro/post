<?php


namespace App\Repository\Email;


use App\Models\Email\EmailDomain;
use App\Repository\Eloquent\BaseRepository;

class EmailDomainRepository extends BaseRepository implements EmailDomainRepositoryInterface
{
    /**
     * EmailDomainRepository constructor.
     * @param EmailDomain $model
     */
    public function __construct(EmailDomain $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function firstByEmail($email)
    {
        return $this->model->firstWhere('name', $email);
    }
}
