<?php


namespace App\Http\Services\WriteBase\Duty;


interface DutySaveServiceInterface
{
    /**
     * @param $duties
     * @return mixed
     */
    public function saveDuty($duties);
}
