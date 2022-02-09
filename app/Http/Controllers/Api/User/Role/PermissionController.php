<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\Permission\PermissionRequest;
use App\Http\Resources\User\Permission\PermissionResource;
use App\Models\Permission;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\User\Role\Permission\PermissionRepositoryInterface;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepositoryInterface
     */
    private $permissionRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * PermissionController constructor.
     * @param PermissionRepositoryInterface $permissionRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(PermissionRepositoryInterface $permissionRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->permissionRepository = $permissionRepository;
        $this->authorizeResource(Permission::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PermissionResource::collection($this->permissionRepository->all());
    }

    /**
     * @param PermissionRequest $request
     * @return PermissionResource
     */
    public function store(PermissionRequest $request)
    {
        return new PermissionResource($this->permissionRepository->create($request->validated()));
    }

    /**
     * @param Permission $role
     * @return PermissionResource
     */
    public function show(Permission $role)
    {
        return new PermissionResource($role);
    }

    /**
     * @param PermissionRequest $request
     * @param Permission $permission
     * @return PermissionResource|\Illuminate\Http\JsonResponse
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        try {
                return new PermissionResource($permission->update($request->validated()));
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        return response()->json(['message' => $permission->delete()], 200);
    }

}
