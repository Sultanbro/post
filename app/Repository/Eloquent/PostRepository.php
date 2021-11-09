<?php


namespace App\Repository\Eloquent;


use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Post $model
     */
    public function __construct(Post $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $company_id
     * @return \Ramsey\Collection\Collection
     */
    public function getPostsByCompanyId($company_id)
    {
        return $this->model->whereIn('company_id', $company_id)->get();
    }
}
