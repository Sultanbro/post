<?php

namespace App\Http\Services\User;

use App\Http\Resources\Post\PostResource;
use App\Repository\Client\EOrder\EOrderRepository;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var EOrderRepositoryInterface
     */
    private $eOrederRepository;

    /**
     * UserService constructor.
     * @param EOrderRepositoryInterface $EOrderRepository
     */
    public function __construct(EOrderRepositoryInterface $EOrderRepository)
    {
        $this->eOrederRepository = $EOrderRepository;
    }


    /**
     * @inheritDoc
     */
    public function getCareer(array $clientCompanyId)
    {
        $result = $this->eOrederRepository->getCareer($clientCompanyId);

    }
}
