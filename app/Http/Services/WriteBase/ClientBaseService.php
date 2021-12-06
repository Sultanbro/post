<?php


namespace App\Http\Services\WriteBase;


use App\Repository\CityRepositoryInterface;
use App\Repository\ClientContactRepositoryInterface;
use App\Repository\ClientRepositoryInterface;
use App\Repository\DepartmentRepositoryInterface;
use App\Repository\DictiRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
use App\Repository\EOrderRepositoryInterface;
use App\Repository\RegionRepositoryInterface;
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
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var ClientContactRepositoryInterface
     */
    private $clientContactRepository;
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;
    /**
     * @var RegionRepositoryInterface
     */
    private $regionRepository;
    /**
     * @var EOrderRepositoryInterface
     */
    private $eOrderRepository;

    /**
     * @param ClientRepositoryInterface $clientRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param UserRepositoryInterface $userRepository
     * @param DictiRepositoryInterface $dictiRepository
     * @param ClientContactRepositoryInterface $clientContactRepository
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param CityRepositoryInterface $cityRepository
     * @param RegionRepositoryInterface $regionRepository
     * @param EOrderRepositoryInterface $eOrderRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, DepartmentRepositoryInterface $departmentRepository, UserRepositoryInterface $userRepository, DictiRepositoryInterface $dictiRepository, ClientContactRepositoryInterface $clientContactRepository, EmployeeRepositoryInterface $employeeRepository, CityRepositoryInterface $cityRepository, RegionRepositoryInterface $regionRepository, EOrderRepositoryInterface $eOrderRepository)
    {
        $this->eOrderRepository = $eOrderRepository;
        $this->regionRepository =$regionRepository;
        $this->cityRepository = $cityRepository;
        $this->employeeRepository = $employeeRepository;
        $this->clientContactRepository = $clientContactRepository;
        $this->dictiRepository = $dictiRepository;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function saveClients($clients)
    {
        $user_make = ['created_by' => Auth::id(), 'updated_by'=> Auth::id(), 'password' => 12345678];

        foreach ($clients as $client) {
//            return $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id']);
            $client_info = array_merge($user_make, $client);
                if ($parent_foreign = $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id'])) {
                    if ($clientModel = $this->clientRepository->firstWhereForeignId($client['foreign_id'], $client['company_id'])) {
                        if ($clientModel->type_id == 1) {
                            if (!$this->departmentRepository->firstWhereId($clientModel->id)) {
                                $this->saveDepartment($clientModel->id, $parent_foreign->id, $client_info, $user_make);
                            } else {
                                $result[] = ['message' => 'this dept is in the base', $client];
                            }
                        }
                        if ($clientModel->type_id == 2 or $clientModel->type_id == 3) {

                            if (!$this->userRepository->userById($clientModel->id)) {

                                $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);
                            }else{
                                $result[] = ['message' => 'this user is in the base', $client];
                            }
                        }
                    } else {
                        $client['address'] = json_encode($client['address']);
                        if ($clientModel = $this->clientRepository->create(array_merge($client, $user_make))) {

                            if ($clientModel->type_id == 1) {

                                $this->saveDepartment($clientModel->id, $parent_foreign->id, $client_info, $user_make);
                            }
                            if ($clientModel->type_id == 2 or $clientModel->type_id == 3) {

                                $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);
                            }
                        }
                    }
                }else{
                    $result[] = $client;
                }
        }
        if (isset($result[0])) {
            return response()->json($result);
        }else{
            return response()->json(['message' => 'ok'], 200);
        }
    }

    /**
     * @param $client_info
     * @param $parent_foreign_id
     * @param $clientModel_id
     * @param $user_make
     */
    public function saveUsers($client_info, $parent_foreign_id, $clientModel_id, $user_make)
    {
        if ($model = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['duty_id'],$client_info['company_id'])) {
            $client_info['duty_id'] = $model->id;
        }else{
            $client_info['duty_id'] = 6;
        }
        $this->userRepository->create(array_merge(['id' => $clientModel_id, 'department_id' => $parent_foreign_id], $client_info));
        $this->saveContact($client_info, $clientModel_id, $user_make);
        $city_id = $this->cityRepository->firstForeignCompanyId($client_info['city_id'], $client_info['company_id'])->id;
        $region_id = $this->regionRepository->firstByForeignIdCompanyId($client_info['region_id'], $client_info['company_id'])->id;
        $country_id = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['country_id'], $client_info['company_id'])->id;
        $client_info['employee']['nation_id'] = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['employee']['nation_id'], $client_info['company_id'])->id;
        $client_info['employee']['science_id'] = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['employee']['science_id'], $client_info['company_id'])->id;
        $this->employeeRepository->create(array_merge(array_merge(['id' => $clientModel_id, 'city_id' => $city_id, 'region_id' => $region_id, 'country_id' => $country_id], $client_info['employee']), $user_make));
    }

    /**
     * @param $clientModel_id
     * @param $parent_foreign_id
     * @param $client_info
     * @param $user_make
     */
    public function saveDepartment($clientModel_id, $parent_foreign_id, $client_info, $user_make)
    {
        $this->departmentRepository->create(array_merge(['id' => $clientModel_id, 'parent_id' => $parent_foreign_id], $client_info));
        $this->saveContact($client_info, $clientModel_id, $user_make);
    }

    /**
     * @param $client_info
     * @param $clientModel_id
     * @param $user_make
     */
    public function saveContact($client_info, $clientModel_id, $user_make)
    {
        $dictiModel = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['contact']['type_id'], $client_info['company_id']);
        $client_info['contact']['type_id'] = $dictiModel->id;
        $client_info['contact']['contact_id'] = $clientModel_id;
        $client_info['contact']['client_id'] = $clientModel_id;
        $client_info['contact']['id'] = $clientModel_id;
        $this->clientContactRepository->create(array_merge($client_info['contact'], $user_make));
    }

    public function acceptEOrder($e_orders)
    {
        foreach ($e_orders as $e_order) {
            if ($e_orders['type_id'] = $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['type_id'], $e_order['company_id'])) {
                if ($e_order['department_id'] = $this->clientRepository->firstWhereForeignId($e_order['department_id'], $e_order['company_id'])) {
                    try {
                        $e_order['client_id'] = is_null($e_order['client_id']) ? null : $this->clientRepository->firstWhereForeignId($e_order['client_id'], $e_order['company_id'])->id;
                        $e_order['release_id'] = is_null($e_orders['release_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['release_id'], $e_order['company_id'])->id;
                        $e_order['client_type'] = is_null($e_orders['client_type']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['client_type'], $e_order['company_id'])->id;
                        $e_order['agr_type_id'] = is_null($e_orders['agr_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['agr_type_id'], $e_order['company_id'])->id;
                        $e_order['vacation_type_id'] = is_null($e_orders['vacation_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['vacation_type_id'], $e_order['company_id'])->id;
                        $e_order['mission_type_id'] = is_null($e_orders['mission_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['mission_type_id'], $e_order['company_id'])->id;
                        $e_order['country_id'] = is_null($e_orders['country_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['country_id'], $e_order['company_id'])->id;
                        $e_order['city_id'] = is_null($e_orders['city_id']) ? null : $this->cityRepository->firstForeignCompanyId($e_orders['city_id'], $e_order['company_id'])->id;
                        $e_order['firm_id'] = is_null($e_orders['firm_id']) ? null : $this->clientRepository->firstWhereForeignId($e_orders['firm_id'], $e_order['company_id'])->id;
                        $e_order['thanks_id'] = is_null($e_orders['thanks_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['thanks_id'], $e_order['company_id'])->id;
                        $e_order['career_reason_id'] = is_null($e_orders['career_reason_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['career_reason_id'], $e_order['company_id'])->id;
                        $e_order['doc_id'] = is_null($e_orders['doc_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_orders['doc_id'], $e_order['company_id'])->id;
                        $this->eOrderRepository->create(array_merge($e_order, ['created_by' => Auth::id(), 'updated_by'=> Auth::id()]));
                    }catch (\Exception $e) {
                        $result[$e_order['isn']] = $e;
                    }
                }else {
                    $result[$e_order['isn']] = ['message' => 'no department_id'];
                }
            }else {
                $result[$e_order['isn']] = ['message' => 'no type_id'];
            }
        }
    }

}
