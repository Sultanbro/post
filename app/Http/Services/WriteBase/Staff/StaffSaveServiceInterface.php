<?php


namespace App\Http\Services\WriteBase\Staff;


interface StaffSaveServiceInterface
{
    /**
     * @param $duties
     * @return mixed
     */
    public function saveStaff($duties);
}
