<?php


namespace App\Http\Services\WriteBase\Client;


use App\Models\Avatar;
use App\Repository\Client\ClientContact\ClientContactRepositoryInterface;
use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\Reference\City\CityRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Region\RegionRepositoryInterface;
use App\Repository\User\Employee\EmployeeRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use http\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\This;

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
        $this->regionRepository = $regionRepository;
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
        $user_make = ['created_by' => Auth::id(), 'updated_by' => Auth::id(), 'password' => 12345678];

        foreach ($clients as $client) {
//            return $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id']);
            $client_info = array_merge($user_make, $client);
            if ($parent_foreign = $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id'])) {
                if ($clientModel = $this->clientRepository->firstWhereForeignId($client['foreign_id'], $client['company_id'])) {
                    if ($clientModel->type_id == 1) {

                        $result[$client['foreign_id']] = $this->saveDepartment($clientModel->id, $parent_foreign->id, $client_info, $user_make);

                    }
                    if ($clientModel->type_id == 2 or $clientModel->type_id == 3) {

                        $result[$client['foreign_id']] = $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);

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
            } else {
                $result[] = $client;
            }
        }
        if ($result) {
            return response()->json($result);
        } else {
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
        if ($model = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['duty_id'], $client_info['company_id'])) {

            $client_info['duty_id'] = $model->id;
        } else {
            $client_info['duty_id'] = 6;
        }

        if (!$this->userRepository->userById($clientModel_id)) {

            $this->userRepository->create(array_merge(['id' => $clientModel_id, 'department_id' => $parent_foreign_id], $client_info));
            $result['user'] = ['ok'];
        } else {
            $result['user'] = ['message' => 'this is in the base'];
        }

        $result['contact'] = $this->saveContact($client_info, $clientModel_id, $user_make);

        if (!$this->employeeRepository->firstById($clientModel_id)) {
            $city_id = $this->cityRepository->firstForeignCompanyId($client_info['city_id'], $client_info['company_id'])->id;
            $region_id = $this->regionRepository->firstByForeignIdCompanyId($client_info['region_id'], $client_info['company_id'])->id;
            $country_id = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['country_id'], $client_info['company_id'])->id;
            $client_info['employee']['nation_id'] = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['employee']['nation_id'], $client_info['company_id'])->id;
            $client_info['employee']['science_id'] = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['employee']['science_id'], $client_info['company_id'])->id;
            $this->employeeRepository->create(array_merge(array_merge(['id' => $clientModel_id, 'city_id' => $city_id, 'region_id' => $region_id, 'country_id' => $country_id], $client_info['employee']), $user_make));
            $result['employee'] = ['ok'];
        } else {
            $result['employee'] = ['message' => 'this is in the base'];
        }
        return $result;

    }

    /**
     * @param $clientModel_id
     * @param $parent_foreign_id
     * @param $client_info
     * @param $user_make
     */
    public function saveDepartment($clientModel_id, $parent_foreign_id, $client_info, $user_make)
    {
        if (!$this->departmentRepository->firstWhereId($clientModel_id)) {

            $this->departmentRepository->create(array_merge(['id' => $clientModel_id, 'parent_id' => $parent_foreign_id], $client_info));
            $result['department'] = 'ok';

        } else {

            $result['department'] = ['message' => 'this is in the base'];
        }

        $result['contact'] = $this->saveContact($client_info, $clientModel_id, $user_make);

        return $result;
    }

    /**
     * @param $client_info
     * @param $clientModel_id
     * @param $user_make
     */
    public function saveContact($client_info, $clientModel_id, $user_make)
    {
        if (!$this->clientContactRepository->getByClientId($clientModel_id)) {

            if ($dictiModel = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['contact']['type_id'], $client_info['company_id'])) {

                $client_info['contact']['type_id'] = $dictiModel->id;
                $client_info['contact']['contact_id'] = $clientModel_id;
                $client_info['contact']['client_id'] = $clientModel_id;
                $client_info['contact']['id'] = $clientModel_id;
                $this->clientContactRepository->create(array_merge($client_info['contact'], $user_make));

                return 'ok';

            } else {

                return ['message' => 'this contact_type not found in dicti base'];
            }

        } else {

            return ['message' => 'this is in the base'];
        }
    }

    public function acceptEOrder($e_orders)
    {
        foreach ($e_orders as $e_order) {

            if (!$this->eOrderRepository->firstForeignIdCompanyId($e_order['foreign_id'], $e_order['company_id'])) {

                if ($type = $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['type_id'], $e_order['company_id'])) {

                    $e_order['type_id'] = $type->id;
                    if ($dept = $this->clientRepository->firstWhereForeignId($e_order['department_id'], $e_order['company_id'])) {

                        $e_order['department_id'] = $dept->id;
                        try {
                            $e_order['client_id'] = is_null($e_order['client_id']) ? null : $this->clientRepository->firstWhereForeignId($e_order['client_id'], $e_order['company_id'])->id;
                            $e_order['release_id'] = is_null($e_order['release_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['release_id'], $e_order['company_id'])->id;
                            $e_order['client_type'] = is_null($e_order['client_type']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['client_type'], $e_order['company_id'])->id;
                            $e_order['agr_type_id'] = is_null($e_order['agr_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['agr_type_id'], $e_order['company_id'])->id;
                            $e_order['vacation_type_id'] = is_null($e_order['vacation_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['vacation_type_id'], $e_order['company_id'])->id;
                            $e_order['mission_type_id'] = is_null($e_order['mission_type_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['mission_type_id'], $e_order['company_id'])->id;
                            $e_order['country_id'] = is_null($e_order['country_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['country_id'], $e_order['company_id'])->id;
                            $e_order['city_id'] = is_null($e_order['city_id']) ? null : $this->cityRepository->firstForeignCompanyId($e_order['city_id'], $e_order['company_id'])->id;
                            $e_order['firm_id'] = is_null($e_order['firm_id']) ? null : $this->clientRepository->firstWhereForeignId($e_order['firm_id'], $e_order['company_id'])->id;
                            $e_order['thanks_id'] = is_null($e_order['thanks_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['thanks_id'], $e_order['company_id'])->id;
                            $e_order['career_reason_id'] = is_null($e_order['career_reason_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['career_reason_id'], $e_order['company_id'])->id;
                            $e_order['doc_id'] = is_null($e_order['doc_id']) ? null : $this->dictiRepository->firstWhereForeignIdCompanyId($e_order['doc_id'], $e_order['company_id'])->id;
                            $this->eOrderRepository->create(array_merge($e_order, ['created_by' => Auth::id(), 'updated_by' => Auth::id()]));
                        } catch (\Exception $e) {
                            $result[$e_order['foreign_id']] = $e . '';
                        }
                    } else {
                        $result[$e_order['foreign_id']] = ['message' => 'no department_id'];
                    }
                } else {
                    $result[$e_order['foreign_id']] = ['message' => 'no type_id'];
                }
            } else {
                $result[$e_order['foreign_id']] = ['message' => 'this foreign_id in base'];
            }
        }
        $result = isset($result) ? $result : ['message' => 'ok'];
        return response()->json($result);
    }

    /**
     * @param $req
     * @return string[]
     */
    public function acceptAvatar($req)
    {
        $result = [];
        foreach ($req as $re) {

            if (!$model = $this->clientRepository->firstWhereForeignId($re['foreign_id'], $re['company_id'])) {

                $result = [$re['foreign_id'] => 'not found foreign_id'];
            } else {
                $result = $this->saveAvatar($re, $model->id);
            }
        }
        return $result;

    }

    /**
     * @param $req
     * @param $user_id
     * @return string[]
     */
    public function saveAvatar($req, $user_id)
    {
        $content = file_get_contents($req['file']->getRealPath());
        $fileName = $req['file']->getClientOriginalName();
        Storage::disk('local')->put("public/avatars/$user_id/$fileName", $content);
        Avatar::firstOrCreate(['link' => "storage/$user_id/$fileName", 'user_id' => $user_id]);
        return [$req['foreign_id'] => 'ok'];
    }
}
