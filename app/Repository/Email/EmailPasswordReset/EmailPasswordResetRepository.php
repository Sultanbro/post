<?php

namespace App\Repository\Email\EmailPasswordReset;

use App\Models\Email\EmailPasswordReset;
use App\Repository\Eloquent\BaseRepository;

class EmailPasswordResetRepository extends BaseRepository implements EmailPasswordResetRepositoryInterface
{
    /**
     * EmailPasswordResetRepository constructor.
     * @param EmailPasswordReset $model
     */
    public function __construct(EmailPasswordReset $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}
