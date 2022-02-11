<?php

namespace App\Http\Resources\User\Role;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\User\Permission\PermissionResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class RoleResource extends JsonResource
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
          'id' => $this->id,
          'name' => $this->name,
          'slug' => $this->slug,
          'company_info' => new CompanyResource($this->company),
            'permissions' => PermissionResource::collection($this->permissions),
            'permission' => ['update' => $this->when(Gate::allows('update_role'), 'update'),
                             'crate' => $this->when(Gate::allows('create_role'), 'create'),
                             'delete' => $this->when(Gate::allows('delete_role'), 'delete'),],
        ];
    }
}
