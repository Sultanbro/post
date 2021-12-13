<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\Duty\StaffSaveServiceInterface;
use Illuminate\Http\Request;

class DutyController extends Controller
{
    /**
     * @var StaffSaveServiceInterface
     */
    private $dutyService;

    public function __construct(StaffSaveServiceInterface $dutyService)
    {
        $this->dutyService = $dutyService;
    }

    public function saveDuties(Request $request)
    {
        return $this->dutyService->saveDuty($request->all());
    }
}
