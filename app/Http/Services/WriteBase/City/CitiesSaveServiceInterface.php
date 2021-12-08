<?php


namespace App\Http\Services\WriteBase\City;


interface CitiesSaveServiceInterface
{
    /**
     * @param $cities
     * @return mixed
     */
    public function saveCities($cities);
}
