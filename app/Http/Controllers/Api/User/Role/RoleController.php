<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Resources\User\Role\RoleResource;
use App\Models\Role;
use App\Models\User;
use App\Repository\User\Role\RoleRepositoryInterface;
use App\Traits\HasRolesAndPermissions;
use http\Env\Response;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PhpParser\Builder;

class RoleController extends Controller
{
    use HasRolesAndPermissions;
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
        $this->authorizeResource(Role::class);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return (RoleResource::collection($this->roleRepository->getByRoleCompany('role_index')))->additional(['role_index' => [1, 2]]);
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
        return new RoleResource($this->roleRepository->firstByRoleCompany($role, 'role_index'));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        return response()->json(['message' => $role->delete()], 200);
    }
}
