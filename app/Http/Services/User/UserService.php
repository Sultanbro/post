<?php

namespace App\Http\Services\User;

use App\Http\Resources\Post\PostResource;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepository;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var EOrderRepositoryInterface
     */
    private $eOrederRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentReposytory;

    /**
     * UserService constructor.
     * @param EOrderRepositoryInterface $EOrderRepository
     * @param UserRepositoryInterface $userRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(EOrderRepositoryInterface $EOrderRepository, UserRepositoryInterface $userRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentReposytory = $departmentRepository;
        $this->userRepository = $userRepository;
        $this->eOrederRepository = $EOrderRepository;
    }


    /**
     * @inheritDoc
     */
    public function getCareer(array $clientCompanyId)
    {
        $result = $this->eOrederRepository->getCareer($clientCompanyId);

    }


    /**
     * @param $params
     * @return mixed|void
     */
    public function saveRole($params)
    {
        if (isset($params['department_id'])) {
            $users = $this->userRepository->getUserByDepartmentId($this->getDeptsByDepartmentId($params['department_id']));
        }
        if (isset($params['user_id'])) {
                $users[] = $this->userRepository->find($params['user_id']);
        }
        foreach ($users as $user) {
            if (is_null($params['role_id'][0])) {
                $user->roles()->detach();
            }else {
                $user->roles()->attach($params['role_id']);
            }
        }
        return 'ok';
    }

    public function getDeptsByDepartmentId($department_id)
    {
        return $this->getAllDept($this->departmentReposytory->find($department_id));
    }

    public function getAllDept($deptModel)
    {
        $dept[] = $deptModel->id;
        $deptModels = $deptModel->department;
       if (count($deptModels) != 0) {
            foreach ($deptModels as $deptModel) {
           $dept = array_merge($this->getAllDept($deptModel), $dept);
            }
       }
        return $dept;
    }

}
