<?php

namespace App\Http\Resources;

use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Department\DepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'department' => new DepartmentResource($this->userDepartment),
//            'company' => new CompanyResource(data_get($this, 'userCompany')),
            'user_info' => new ClientResource($this->clientInfo),
            'order_info' => $this->eOrder,
        ];
    }
}
