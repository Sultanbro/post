<?php


namespace App\Http\Services\WriteBase;


use App\Repository\CityRepositoryInterface;
use App\Repository\ClientContactRepositoryInterface;
use App\Repository\ClientRepositoryInterface;
use App\Repository\DepartmentRepositoryInterface;
use App\Repository\DictiRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
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
     * @param ClientRepositoryInterface $clientRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param UserRepositoryInterface $userRepository
     * @param DictiRepositoryInterface $dictiRepository
     * @param ClientContactRepositoryInterface $clientContactRepository
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param CityRepositoryInterface $cityRepository
     * @param RegionRepositoryInterface $regionRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository, DepartmentRepositoryInterface $departmentRepository, UserRepositoryInterface $userRepository, DictiRepositoryInterface $dictiRepository, ClientContactRepositoryInterface $clientContactRepository, EmployeeRepositoryInterface $employeeRepository, CityRepositoryInterface $cityRepository, RegionRepositoryInterface $regionRepository)
    {
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

    public function saveContact($client_info, $clientModel_id, $user_make)
    {
        $dictiModel = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['contact']['type_id'], $client_info['company_id']);
        $client_info['contact']['type_id'] = $dictiModel->id;
        $client_info['contact']['contact_id'] = $clientModel_id;
        $client_info['contact']['client_id'] = $clientModel_id;
        $client_info['contact']['id'] = $clientModel_id;
        $this->clientContactRepository->create(array_merge($client_info['contact'], $user_make));
    }

}
