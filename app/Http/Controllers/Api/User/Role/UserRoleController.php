<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\UserRoleRequest;
use App\Http\Resources\User\UserRole\UserRoleResource;
use App\Http\Services\User\UserServiceInterface;
use App\Models\User;
use App\Models\UsersRole;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use Doctrine\DBAL\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserRoleController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * UserRoleController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param UserServiceInterface $userService
     */
    public function __construct(UserRepositoryInterface $userRepository, DepartmentRepositoryInterface $departmentRepository, UserServiceInterface $userService)
    {
        $this->departmentRepository = $departmentRepository;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
            return (UserRoleResource::collection($this->userRepository->getByRoleCompany('show_user_role')))
                ->additional($this->departmentRepository->getAccessCompany('show_user_role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRoleRequest $request)
    {
        return $this->userService->saveRole($request->all());
    }

    /**
     * @param $id
     * @return UserRoleResource
     */
    public function show($id)
    {
            return new UserRoleResource($this->userRepository->userById($id));
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        return response()->json(['message' => $this->userRepository->userById($id)->roles()->detach()], 200);
    }
}
