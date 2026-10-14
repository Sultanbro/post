<?php
namespace App\Repository\Email;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;


interface EmailDomainRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $email
     * @return mixed
     */
    public function firstByEmail($email);
}
