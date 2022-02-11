<?php

namespace App\Http\Resources\User\UserRole;

use App\Http\Resources\User\Permission\PermissionResource;
use App\Http\Resources\User\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class UserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_info' => $this->id,
            'role' => RoleResource::collection($this->roles),
            'permission' => ['update' => $this->when(Gate::allows('update_user_role'), 'update'),
                'crate' => $this->when(Gate::allows('create_user_role'), 'create'),
                'delete' => $this->when(Gate::allows('delete_user_role'), 'delete'),],
        ];
    }
}
