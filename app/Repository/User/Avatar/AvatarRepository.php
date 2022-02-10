<?php


namespace App\Repository\User\Avatar;


use App\Models\Avatar;
use App\Repository\Eloquent\BaseRepository;

class AvatarRepository extends BaseRepository implements AvatarRepositoryInterface
{
    /**
     * Avatar constructor.
     * @param Avatar $model
     */
    public function __construct(Avatar $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
