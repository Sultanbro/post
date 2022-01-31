<?php

namespace App\Http\Resources\User;

use App\Http\Controllers\Api\User\UserController;
use App\Http\Resources\Department\DepartmentResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $_monthsList = array(
            "01" => "Января",
            "02" => "Февраля",
            "03" => "Марта",
            "04" => "Апреля",
            "05" => "Мая",
            "06" => "Июня",
            "07" => "Июля",
            "08" => "Августа",
            "09" => "Сентября",
            "10" => "Октября",
            "11" => "Ноября",
            "12" => "Декабря"
        );
        return [
            'id' => $this->id,
            'full_name' => $this->clientInfo->full_name,
            'birthday' => date_format( date_create($this->clientInfo->birthday), 'd') .' '. $_monthsList[date_format( date_create($this->clientInfo->birthday), 'm')],
            'department' => new DepartmentResource($this->userDepartment),
            'avatar' => $this->clientInfo->avatar,
        ];
    }
}
