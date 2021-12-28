<?php

namespace App\Http\Resources;

use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Department\DepartmentResource;
use App\Http\Resources\User\CareerResource;
use App\Http\Resources\User\UserContactResource;
use App\Http\Resources\User\UserHistoryResource;
use App\Http\Resources\User\VacationResource;
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
            'user_info' => new ClientResource($this->clientInfo),
            'user_contact' => new UserContactResource($this->clientContact),
            'user_career' => CareerResource::collection($this->career),
            'user_vacation' => VacationResource::collection($this->vacation),
        ];
    }
}
