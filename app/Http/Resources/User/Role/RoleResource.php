<?php

namespace App\Http\Resources\User\Role;

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
          'name' => $this->name,
          'slug' => $this->slug,
          'id' => $this->id,
            'permissions' => PermissionResource::collection($this->permissions),
            'permission' => ['update' => $this->when(Gate::allows('update_post'), 'update'),
                             'crate' => $this->when(Gate::allows('create'), 'create')],
        ];
    }
}
