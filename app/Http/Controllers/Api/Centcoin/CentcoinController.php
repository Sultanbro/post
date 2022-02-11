<?php

namespace App\Http\Controllers\Api\Centcoin;

use App\Http\Controllers\Controller;
use App\Repository\Centcoin\CentcoinRepositoryInterface;
use App\Http\Services\Centcoin\CentcoinServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CentcoinController extends Controller
{
    /**
     * @var CentcoinRepositoryInterface
     * @var CentcoinServiceInterface
     */
    private $centcoinRepository;
    private $centcoinService;

    /**
     * @param CentcoinRepositoryInterface $centcoinRepository
     * @param CentcoinServiceInterface $centcoinService
     */
    public function __construct(CentcoinRepositoryInterface $centcoinRepository, CentcoinServiceInterface $centcoinService)
    {
        $this->centcoinRepository = $centcoinRepository;
        $this->centcoinService = $centcoinService;
    }

    /**
     * @return Collection
     */
    public function index()
    {
        return $this->centcoinRepository->all();
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $created_by_id = Auth::id();
        return $this->centcoinService->transaction($request->all(),$created_by_id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        if($this->centcoinRepository->find($id)){
            return response()->json(['success' => true, 'data' => $this->centcoinRepository->find($id)], 200);
        }else {
            return response()->json(['success' => false, 'message' => 'This user not found'], 404);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id){
        if($this->centcoinRepository->find($id)){
            $coin = $this->centcoinRepository->find($id);
            return response()->json(['success' => $coin->update($request->all()),'message' => 'Centcoin of user updated'], 200);
        }else {
            return response()->json(['message' => 'This user not found for update','error' => 'Enter correct id'], 404);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if($this->centcoinRepository->find($id)){
            return response()->json(['message' => 'Centcoin of user delete','success' => $this->centcoinRepository->deleteById($id)],200);
        } else {
            return response()->json(['message' => 'This user not found for delete','error' => 'Enter correct id'], 404);
        }
    }
}
