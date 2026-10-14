<?php


namespace App\Repository\User\Career;


use App\Models\Client\UserStory\CareerUser;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class CareerUserRepository extends BaseRepository implements CareerUserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param CareerUser $model
     */
    public function __construct(CareerUser $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function getByForeign_idAndCompany_id($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
