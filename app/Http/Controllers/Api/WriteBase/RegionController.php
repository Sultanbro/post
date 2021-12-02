<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\RegionSaveServiceInterface;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * @var RegionSaveServiceInterface
     */
    private $service;

    /**
     * CityController constructor.
     * @param RegionSaveServiceInterface $service
     */
    public function __construct(RegionSaveServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveRegions(Request $request)
    {
        return $this->service->saveRegions($request->all());
    }
}
