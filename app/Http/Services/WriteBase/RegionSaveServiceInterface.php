<?php


namespace App\Http\Services\WriteBase;


interface RegionSaveServiceInterface
{
    /**
     * @param $regions
     * @return mixed
     */
    public function saveRegions($regions);
}
