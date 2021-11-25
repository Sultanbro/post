<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\DictisSaveServiceInterface;
use App\Repository\DictiRepositoryInterface;
use Illuminate\Http\Request;

class DictisController extends Controller
{

    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var DictisSaveServiceInterface
     */
    private $dictiService;

    /**
     * DictisController constructor.
     * @param DictiRepositoryInterface $dictiRepository
     * @param DictisSaveServiceInterface $dictiService
     */
    public function __construct(DictiRepositoryInterface $dictiRepository, DictisSaveServiceInterface $dictiService)
    {
        $this->dictiService = $dictiService;
        $this->dictiRepository = $dictiRepository;
    }

    public function acceptDictisInfo(Request $request)
    {
        return $this->dictiService->saveDictis($request->all());
    }
}
