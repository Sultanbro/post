<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Resources\User\Role\RoleResource;
use App\Models\Role;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\User\Role\RoleRepositoryInterface;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use HasRolesAndPermissions;
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * RoleController constructor.
     * @param RoleRepositoryInterface $roleRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return (RoleResource::collection($this->roleRepository->getByRoleCompany('index_role')))
            ->additional($this->departmentRepository->getAccessCompany('index_role'));
    }

    /**
     * @param RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleStoreRequest $request)
    {
        $roleModel = $this->roleRepository->create($request->all());
        $roleModel->permissions()->attach($request->permissions);
        return new RoleResource($roleModel);
    }

    /**
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($this->roleRepository->firstByRoleCompany($role, 'show_role'));
    }

    /**
     * @param RoleStoreRequest $request
     * @param Role $role
     * @return RoleResource|\Illuminate\Http\JsonResponse
     */
    public function update(RoleStoreRequest $request, Role $role)
    {
        try {
            if ($role->update($request->validated())) {
                $role->permissions()->sync($request->permissions);
                return new RoleResource($role);
            }
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $this->roleRepository->firstByRoleCompany($role, 'delete_role');
        return response()->json(['message' => $role->delete()], 200);
    }
}
