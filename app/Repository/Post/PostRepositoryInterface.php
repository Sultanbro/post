<?php
namespace App\Repository\Post;

use Ramsey\Collection\Collection;
use App\Repository\Eloquent\EloquentRepositoryInterface;

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

    /**
     * @param $id
     * @return mixed
     */
    public function getByPostId($id);

    /**
     * @param $id
     * @return mixed
     */
    public function firstById($id);



    /**
     * @param $params
     * @return mixed
     */
    public function getFilterPosts($params);
}
