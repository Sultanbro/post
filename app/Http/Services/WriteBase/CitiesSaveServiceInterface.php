<?php


namespace App\Http\Services\WriteBase;


interface CitiesSaveServiceInterface
{
    /**
     * @param $cities
     * @return mixed
     */
    public function saveCities($cities);
}
