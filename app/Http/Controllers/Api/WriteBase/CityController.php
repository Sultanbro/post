<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\CitiesSaveService;
use App\Http\Services\WriteBase\CitiesSaveServiceInterface;
use App\Repository\CityRepositoryInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @var CitiesSaveServiceInterface
     */
    private $service;

    /**
     * CityController constructor.
     * @param CitiesSaveServiceInterface $service
     */
    public function __construct(CitiesSaveServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveCities(Request $request)
    {
        return $this->service->saveCities($request->all());
    }
}
