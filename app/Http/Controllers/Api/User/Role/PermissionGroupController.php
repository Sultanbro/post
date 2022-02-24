<?php

namespace App\Http\Controllers\Api\User\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\Permission\PermissionRequest;
use App\Http\Resources\User\PermissionGroup\PermissionGroupResource;
use App\Models\PermissionGroup;
use App\Repository\User\Role\PermissionGroup\PermissionGroupRepositoryInterface;
use Illuminate\Http\Request;

class PermissionGroupController extends Controller
{
    /**
     * @var PermissionGroupRepositoryInterface
     */
    private $permissionGroupRepository;

    /**
     * @param PermissionGroupRepositoryInterface $permissionGroupRepository
     */
    public function __construct(PermissionGroupRepositoryInterface $permissionGroupRepository)
    {
        $this->permissionGroupRepository = $permissionGroupRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PermissionGroupResource::collection($this->permissionGroupRepository->all());
    }

    /**
     * @param PermissionRequest $request
     * @return PermissionGroupResource
     */
    public function store(Request $request)
    {
        $model = $this->permissionGroupRepository->create($request->all());

        return new PermissionGroupResource($model);
    }

    /**
     * @param PermissionGroup $permissionGroup
     * @return PermissionGroupResource
     */
    public function show(PermissionGroup $permissionGroup)
    {
        return new PermissionGroupResource($permissionGroup);
    }

    /**
     * @param PermissionRequest $request
     * @param PermissionGroup $permissionGroup
     * @return PermissionGroupResource
     */
    public function update(Request $request, PermissionGroup $permissionGroup)
    {
        try {
                return new PermissionGroupResource($permissionGroup->update($request->all()));
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param PermissionGroup $permissionGroup
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PermissionGroup $permissionGroup)
    {
        return response()->json(['message' => $permissionGroup->delete()], 200);
    }

}
