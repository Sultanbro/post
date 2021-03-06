<?php


namespace App\Repository\User\Staff;


use App\Models\Client\UserStory\StaffUser;
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

    /**
     * @param $foreign_id
     * @param $company_id
     * @return mixed|void
     */
    public function getByForeignIdCompanyId($foreign_id, $company_id)
    {
        return $this->model->whereForeign_idAndCompany_id($foreign_id, $company_id)->first();
    }
}
