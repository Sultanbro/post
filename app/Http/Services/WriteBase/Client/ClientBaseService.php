<?php


namespace App\Http\Services\WriteBase\Client;


use App\Http\Services\Authenticate\KeyCloakServiceInterface;
use App\Models\Avatar;
use App\Repository\Client\ClientContact\ClientContactRepositoryInterface;
use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\Reference\City\CityRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Region\RegionRepositoryInterface;
use App\Repository\User\Avatar\AvatarRepositoryInterface;
use App\Repository\User\Employee\EmployeeRepositoryInterface;
use App\Repository\User\UserDetailsRepository;
use App\Repository\User\UserRepositoryInterface;
use http\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;
use const http\Client\Curl\Features\HTTP2;

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
     * @var KeyCloakServiceInterface
     */
    private $cloakService;
    /**
     * @var UserDetailsRepository
     */
    private $userDetailsRepository;
    /**
     * @var AvatarRepositoryInterface
     */
    private $avatarRepository;

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
     * @param KeyCloakServiceInterface $cloakService
     * @param UserDetailsRepository $userDetailsRepository
     * @param AvatarRepositoryInterface $avatarRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository,
                                DepartmentRepositoryInterface $departmentRepository,
                                UserRepositoryInterface $userRepository,
                                DictiRepositoryInterface $dictiRepository,
                                ClientContactRepositoryInterface $clientContactRepository,
                                EmployeeRepositoryInterface $employeeRepository,
                                CityRepositoryInterface $cityRepository,
                                RegionRepositoryInterface $regionRepository,
                                EOrderRepositoryInterface $eOrderRepository,
                                KeyCloakServiceInterface $cloakService,
                                UserDetailsRepository $userDetailsRepository,
                                AvatarRepositoryInterface $avatarRepository)
    {
        $this->avatarRepository = $avatarRepository;
        $this->userDetailsRepository = $userDetailsRepository;
        $this->cloakService = $cloakService;
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
        $user_make = ['created_by' => Auth::id(), 'updated_by' => Auth::id()];
//        $result['request'] = $clients;

        try {

            foreach ($clients as $client) {
//            return $this->clientRepository->firstWhereForeignId($client['parent_foreign_id'], $client['company_id']);
                $client_info = array_merge($user_make, $client);
                if ($parent_foreign = $this->departmentRepository->firstWhereForeignIdCompanyId($client['parent_foreign_id'], $client['company_id'])) {
                if ($client['type_id'] == 2) {
                        if (!$departmentModel = $this->departmentRepository->firstWhereForeignIdCompanyId($client['foreign_id'], $client['company_id'])) {
                            $client['address'] = isset($client['address']) ? json_encode($client['address']) : null;
                            if ($clientModel = $this->clientRepository->create(array_merge($client, $user_make))) {
                                $result[$client['foreign_id']] = $this->saveDepartment($clientModel->id, $parent_foreign->id, $client_info, $user_make);
                            }
                        }else{
                            $result[$client['foreign_id']] = ['client' => 'is in a base'];
                        }
                }
                if ($client['type_id'] == 3 or $client['type_id'] == 4) {
                    if ($userModel = $this->userRepository->getByForeignIdAndCompany_id($client['foreign_id'], $client['company_id'])) {
                        $this->userRepository->update($userModel->id, array_merge($client, $user_make));
                        $client['address'] = isset($client['address']) ? json_encode($client['address']) : null;
                        $this->clientRepository->update($userModel->client_id, array_merge($client, $user_make));
                        $result[$client['foreign_id']] = ['this user in base'];
                    }else{
                        if ($clientModel = $this->clientRepository->firstClientByIin($client['iin'])) {
                            $result[$client['foreign_id']] = $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);
                        } else {
                            $client['address'] = isset($client['address']) ? json_encode($client['address']) : null;
                            if ($clientModel = $this->clientRepository->create(array_merge($client, $user_make))) {
                                if ($user = $this->userRepository->userFromEmail($client['email'])) {
                                    if ($user->client_id != $clientModel->id) {
                                        continue;
                                    } else {
                                        $result[$client['foreign_id']] = $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);
                                    }
                                }else{
                                    $result[$client['foreign_id']] = $this->saveUsers($client_info, $parent_foreign->id, $clientModel->id, $user_make);
                                }
                            }
                        }
                    }
                }
                }else{
                    $result[$client['foreign_id']] = ['is not found parent id'];
                }

            }
        }
        catch (\Exception $exception) {
            return $exception;
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
        try {
            if ($model = $this->dictiRepository->firstWhereForeignIdCompanyId($client_info['duty_id'], $client_info['company_id'])) {

                $client_info['duty_id'] = $model->id;
            } else {
                $client_info['duty_id'] = 6;
            }

                $result['user'] = $this->registerUser($clientModel_id, $parent_foreign_id, $client_info);

            if (isset($client_info['contact'])) {
                $result['contact'] = $this->saveContact($client_info, $clientModel_id, $user_make);
            }

            if (isset($client_info['employee'])) {
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
            }
            return $result;
        }catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * @param $clientModel_id
     * @param $parent_foreign_id
     * @param $client_info
     * @param null $password
     * @return \Exception|mixed|string
     */
    public function registerUser($clientModel_id, $parent_foreign_id, $client_info, $password = null)
    {
        try {
            if (is_null($password)) {
                $password = Str::random(9);
            }

            $client_info['email'] = mb_strtolower($client_info['email']);
            $client_info['username'] = $this->createUsername(stristr($client_info['email'], '@', true));

            if ($cloak = $this->cloakService->registerUser($client_info['email'], $client_info['first_name'], $client_info['parent_name'], $client_info['username'])) {
                $this->userRepository->create(array_merge(['department_id' => $parent_foreign_id, 'password' => $password, 'username' => $client_info['username'], 'client_id' => $clientModel_id], $client_info));
                return 'ok';
            }

            return $cloak;
        }catch (\Exception $exception) {
            return $exception;
        }

    }

    /**
     * @param $username
     * @param int $var
     * @return string
     */
    public function createUsername($username, $var = 0)
    {
        if ($this->userRepository->firstUserByUsername($username)) {
            $var++;
            $username = $this->createUsername($username.$var, $var);
        }
        return $username;
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

        if (isset($client_info['contact'])) {
            $result['contact'] = $this->saveContact($client_info, $clientModel_id, $user_make);
        }

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

            if (!$model = $this->userRepository->getByForeignIdAndCompany_id($re['foreign_id'], $re['company_id'])) {

                $result = [$re['foreign_id'] => 'not found foreign_id'];
            } else {
                $result = [$re['foreign_id'] => $this->saveAvatar($re, $model->client_id)];
            }
        }
        return $result;

    }

    /**
     * @param $req
     * @param $client_id
     * @return array|\Exception|string
     */
    public function saveAvatar($req, $client_id)
    {
        $req['filePath'] = "avatars/$client_id";
        try {
            if (Storage::disk('local')->exists("public/" .$req['filePath'])) {
                Storage::disk('local')->deleteDirectory("public/" .$req['filePath']);
            }
            if (isset($req['url'])) {

                $content = file_get_contents($req['url']);
                $fileName = basename($req['url']);
                $link = $this->saveStorage($fileName, $req['filePath'], $content);

            }elseif (isset($req['file'])) {

                $link = $this->saveFile($req);

            }elseif (isset($req['basefile'])) {

                $content = base64_decode($req['basefile']);
                $link = $this->saveStorage($req['filename'], $req['filePath'], $content);

            }

            $type = isset($req['type']) ? $req['type'] : 1;

            if ($this->avatarRepository->createOrUpdate(['client_id' => $client_id,], ['created_by' => Auth::id(), 'updated_by' => Auth::id(), 'type' => $type, 'link' => $link,])) return [$client_id => 'ok', 'link' => $link];

            return 'not storage';
        }catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function userDetails($params)
    {
        if ($user = $this->userRepository->getByForeignIdAndCompany_id($params['foreign_id'], $params['company_id'])) {
            $params['user_id'] = $user->id;
            $params['user_info'] = json_encode($params['user_info']);
            if ($detail = $this->userDetailsRepository->getByForeignId($user->id)) {
                return $this->userDetailsRepository->update($detail->id, $params);
            }
            return $this->userDetailsRepository->create($params);
        }
    }

    /**
     * @inheritDoc
     */
    public function saveFile($params)
    {
        $content = file_get_contents($params['file']->getRealPath());
        $fileName = $params['file']->getClientOriginalName();
        return $this->saveStorage($fileName, $params['filePath'], $content);
    }

    /**
     * @param $fileName
     * @param $filePath
     * @param $content
     * @return string
     */
    public function saveStorage($fileName, $filePath, $content)
    {
        Storage::disk('local')->put("public/" . $filePath . "/$fileName", $content);
        return "storage/" . $filePath . "/$fileName";
    }

    /**
     * @param $model
     * @return mixed
     */
    public function deleteAvatars($model)
    {
        $link = explode("/", $model->link);
        $link[0] = "public";
        $link = implode("/", $link);
        if (Storage::disk('local')->exists($link)) {
            Storage::disk('local')->delete($link);
        }
        return $model->delete();
    }
}
