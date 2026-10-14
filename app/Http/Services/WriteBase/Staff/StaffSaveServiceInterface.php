<?php


namespace App\Http\Services\WriteBase\Staff;


interface StaffSaveServiceInterface
{
    /**
     * @param $staffs
     * @return mixed
     */
    public function saveStaff($staffs);
}
