<?php


namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Services\WriteBase\Client\ClientBaseServiceInterface;
use App\Repository\Client\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientBaseController
{
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;
    /**
     * @var ClientBaseServiceInterface
     */
    private $clientBaseService;

    /**
     * ClientBaseController constructor.
     * @param ClientRepositoryInterface $clientRepository
     * @param ClientBaseServiceInterface $clientBaseService
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ClientBaseServiceInterface $clientBaseService)
    {
        $this->clientRepository = $clientRepository;
        $this->clientBaseService = $clientBaseService;
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function acceptClientInfo(Request $request)
    {
        try {
            return $this->clientBaseService->saveClients($request->all());
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function acceptEOrder(Request $request)
    {
        try {
            return $this->clientBaseService->acceptEOrder($request->all());
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function acceptAvatar(Request $request)
    {
        try {
            return $this->clientBaseService->acceptAvatar($request->all());
        }catch (\Exception $e) {
            return response()->json($e, 404);
        }
    }

    public function userDetails(Request $request)
    {
        try {
            return $this->clientBaseService->userDetails($request->all());
        }catch(\Exception $e) {
            return response()->json($e,404);
        }
    }

    public function saveFile(Request $request)
    {
        return $this->clientBaseService->saveFile($request->all());
    }
}
