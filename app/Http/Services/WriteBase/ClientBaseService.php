<?php


namespace App\Http\Services\WriteBase;


use App\Repository\ClientRepositoryInterface;
use App\Repository\DepartmentRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use http\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClientBaseService implements ClientBaseServiceInterface
{
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param ClientRepositoryInterface $clientRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, DepartmentRepositoryInterface $departmentRepository, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function saveClients($clients)
    {
        $user_make = ['created_by' => Auth::id(), 'updated_by'=> Auth::id(), 'password' => 12345678];

        foreach ($clients as $client) {
//            return $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id']);
                if ($parent_foreign = $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id'])) {
                    if ($clientModel = $this->clientRepository->firstWhereForeignId($client['foreign_id'], $client['company_id'])) {
                        $client_info = array_merge($user_make, $client);
                        if ($clientModel->type_id == 1) {
                            if (!$this->departmentRepository->firstWhereId($clientModel->id)) {
                                $this->departmentRepository->create(array_merge(['id' => $clientModel->id, 'parent_id' => $parent_foreign->id], $client_info));
                            }
                        }
                        if ($clientModel->type_id == 2 or $clientModel->type_id == 3) {
                            if (!$this->userRepository->userById($clientModel->id)) {
                                $this->userRepository->create(array_merge(['id' => $clientModel->id, 'department_id' => $parent_foreign->id], $client_info));
                            }
                        }
                    } else {
                        if ($clientModel = $this->clientRepository->create(array_merge($client, $user_make))) {
                            $client_info = array_merge($user_make, $client);
                            if ($clientModel->type_id == 1) {
                                $this->departmentRepository->create(array_merge(['id' => $clientModel->id, 'parent_id' => $parent_foreign->id], $client_info));
                            }
                            if ($clientModel->type_id == 2 or $clientModel->type_id == 3) {
                                $this->userRepository->create(array_merge(['id' => $clientModel->id, 'department_id' => $parent_foreign->id], $client_info));
                            }
                        }
                    }
                }else{
                    $no_company_client[] = $client;
                }
        }
        if (isset($no_company_client[0])) {
            return response()->json($no_company_client);
        }else{
            return response()->json(['message' => 'ok'], 200);
        }
    }

}
