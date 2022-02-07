<?php

namespace App\Http\Controllers\Api\Centcoin;

use App\Http\Controllers\Controller;
use App\Http\Services\Centcoin\CentcoinServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CentcoinController extends Controller
{

// Лишний контроллер

    /**
     * @var CentcoinServiceInterface
     */
    private $centcoinService;

    /**
     * @param CentcoinServiceInterface $centcoinService
     */
    public function __construct(CentcoinServiceInterface $centcoinService)
    {
        $this->centcoinService = $centcoinService;
    }

    public function show($id)
    {
        return $this->centcoinService->show($id);
    }

    public function centcoinApply(Request $request)
    {
        $created_id = Auth::id();
        return $this->centcoinService->centcoinApply($request->all(),$created_id);
    }

    // для Админки

    /**
     * @return Response
     */
    public function index()
    {
        return $this->centcoinService->index();
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $created_by_id = Auth::id();
        return $this->centcoinService->store($request->all(),$created_by_id);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function operationCoins(Request $request)
    {
        $created_id = Auth::id();
        return $this->centcoinService->operationCoins($request->all(),$created_id);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function statusApply(Request $request)
    {
        return $this->centcoinService->statusApply($request->all());
    }
}
