<?php

namespace App\Http\Resources\User\PermissionGroup;

use App\Http\Resources\User\Permission\PermissionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionGroupResource extends JsonResource
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
            'group_name' => $this->name,
            'permission' => PermissionResource::collection($this->permissions),
        ];
    }
}
