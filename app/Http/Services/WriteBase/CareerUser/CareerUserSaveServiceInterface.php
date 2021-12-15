<?php


namespace App\Http\Services\WriteBase\CareerUser;


interface CareerUserSaveServiceInterface
{
    /**
     * @param $careers
     * @return mixed
     */
    public function saveCareerUser($careers);
}
