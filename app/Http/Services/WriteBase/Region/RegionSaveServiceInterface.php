<?php


namespace App\Http\Services\WriteBase\Region;


interface RegionSaveServiceInterface
{
    /**
     * @param $regions
     * @return mixed
     */
    public function saveRegions($regions);
}
