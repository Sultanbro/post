<?php
namespace App\Repository;

use Ramsey\Collection\Collection;

interface PostRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $company_id
     * @return Collection
     */
    public function getPostsByCompanyId($company_id);

    /**
     * @return mixed
     */
    public function getPostsWithData();
}
