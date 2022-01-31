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
        $temp_details = '{
                            "duty": "Не заполнено",
                            "city": "Казахстан",
                            "married": "Не заполнено",
                            "edu": "Не заполнено",
                            "pro": "Не заполнено",
                            "datebeg": null,
                            "dateend": 0,
                            "countdays": 0,
                            "contactmail": "Не заполнено",
                            "workphone": "0",
                            "mobilephone": null,
                            "insidephone": null,
                            "AdmDays": 0,
                            "carier": null,
                            "vacation": null,
                            "admin": null
                            }';
        return [
            'id' => $this->id,
            'email' => $this->email,
//            'department' => new DepartmentResource($this->userDepartment),
            'user_info' => new ClientResource($this->clientInfo),
            'user_detail' => isset($this->details->user_info) ? json_decode($this->details->user_info) : json_decode($temp_details),
        ];
    }
}
