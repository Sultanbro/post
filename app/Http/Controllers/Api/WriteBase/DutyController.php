<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\Duty\DutySaveServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DutyController extends Controller
{
    /**
     * @var DutySaveServiceInterface
     */
    private $dutyService;

    public function __construct(DutySaveServiceInterface $dutyService)
    {
        $this->dutyService = $dutyService;
    }

    public function saveDuties(Request $request)
    {
        Log::debug("SAVE DUTIES");
        Log::debug($request);
        return $this->dutyService->saveDuty($request->all());
    }
}
