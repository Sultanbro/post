<?php


namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Requests\WriteBase\AcceptClientRequest;
use App\Http\Services\WriteBase\ClientBaseServiceInterface;
use App\Repository\ClientRepositoryInterface;
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

    public function acceptClientInfo(Request $request)
    {
        try {
            return $this->clientBaseService->saveClients($request->all());
        }catch (\Exception $e) {
            return response()->json($e);
        }
    }
}
