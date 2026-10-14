<?php

namespace App\Http\Controllers\Api\Centcoin;

use App\Http\Controllers\Controller;
use App\Repository\Centcoin\CentcoinApplyRepositoryInterface;
use App\Http\Services\Centcoin\CentcoinServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CentcoinApplyController extends Controller
{

    /**
     * @var CentcoinApplyRepositoryInterface
     * @var CentcoinServiceInterface
     */
    private $centcoinApplyRepository;
    private $centcoinService;

    /**
     * @param CentcoinApplyRepositoryInterface $centcoinApplyRepository
     * @param CentcoinServiceInterface $centcoinService
     */
    public function __construct(CentcoinApplyRepositoryInterface $centcoinApplyRepository, CentcoinServiceInterface $centcoinService)
    {
        $this->centcoinApplyRepository = $centcoinApplyRepository;
        $this->centcoinService = $centcoinService;
    }

    /**
     * @return Collection
     */
    public function index()
    {
        return $this->centcoinApplyRepository->all();
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $created_by_id = Auth::id();
        return $this->centcoinService->applyOperation($request->all(),$created_by_id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        if($this->centcoinApplyRepository->find($id)){
            return response()->json(['success' => true, 'data' => $this->centcoinApplyRepository->find($id)], 200);
        }else {
            return response()->json(['success' => false, 'message' => 'This apply not found'], 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request){
        return $this->centcoinService->statusApply($request->all());
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if($this->centcoinApplyRepository->find($id)){
            return response()->json(['message' => 'Apply delete','success' => $this->centcoinApplyRepository->deleteById($id)],200);
        } else {
            return response()->json(['message' => 'This apply not found for delete','error' => 'Enter correct id'], 404);
        }
    }
}
