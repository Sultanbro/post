<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Resources\User\Role\RoleResource;
use App\Models\Role;
use App\Repository\User\Role\RoleRepositoryInterface;
use http\Env\Response;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * RoleController constructor.
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoleResource::collection($this->roleRepository->all());
    }

    /**
     * @param RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleStoreRequest $request)
    {
        return new RoleResource($this->roleRepository->create($request->validated()));
    }

    /**
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
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
                return new RoleResource($role);
            }
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param Role $role
     */
    public function destroy(Role $role)
    {
        return response()->json(['message' => $role->delete()], 200);
    }
}
