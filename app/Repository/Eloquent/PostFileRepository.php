<?php


namespace App\Repository\Eloquent;


use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\PostFile;
use App\Models\User;
use App\Repository\PostFileRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class PostFileRepository extends BaseRepository implements PostFileRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param PostFile $model
     */
    public function __construct(PostFile $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
