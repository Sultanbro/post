<?php


namespace App\Repository\Post\PostFile;


use App\Models\Post\PostFile;
use App\Repository\Eloquent\BaseRepository;
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
