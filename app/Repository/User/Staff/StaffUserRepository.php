<?php


namespace App\Repository\User\Staff;


use App\Models\StaffUser;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class StaffUserRepository extends BaseRepository implements StaffUserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param StaffUser $model
     */
    public function __construct(StaffUser $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
