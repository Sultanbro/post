<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\Staff\StaffSaveServiceInterface;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * @var StaffSaveServiceInterface
     */
    private $staffSaveService;

    /**
     * StaffController constructor.
     * @param StaffSaveServiceInterface $saveService
     */
    public function __construct(StaffSaveServiceInterface $saveService)
    {
        $this->staffSaveService = $saveService;
    }

    public function saveStaff(Request $request)
    {
        return $this->staffSaveService->saveStaff($request->all());
    }
}
