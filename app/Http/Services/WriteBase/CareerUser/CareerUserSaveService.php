<?php


namespace App\Http\Services\WriteBase\CareerUser;

use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\User\Career\CareerUserRepositoryInterface;
use App\Repository\User\Staff\StaffUserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CareerUserSaveService implements CareerUserSaveServiceInterface
{
    /**
     * @var StaffUserRepositoryInterface
     */
    private $staffRepository;
    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;
    /**
     * @var EOrderRepositoryInterface
     */
    private $eOrderRepository;
    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var CareerUserRepositoryInterface
     */
    private $careerRepository;

    /**
     * StaffSaveService constructor.
     * @param StaffUserRepositoryInterface $staffUserRepository
     * @param EOrderRepositoryInterface $eOrderRepository
     * @param ClientRepositoryInterface $clientRepository
     * @param DictiRepositoryInterface $dictiRepository
     * @param CareerUserRepositoryInterface $careerUserRepository
     */
    public function __construct(StaffUserRepositoryInterface $staffUserRepository, EOrderRepositoryInterface $eOrderRepository, ClientRepositoryInterface $clientRepository, DictiRepositoryInterface $dictiRepository, CareerUserRepositoryInterface $careerUserRepository)
    {

        $this->careerRepository = $careerUserRepository;
        $this->dictiRepository = $dictiRepository;
        $this->clientRepository = $clientRepository;
        $this->eOrderRepository = $eOrderRepository;
        $this->staffRepository = $staffUserRepository;
    }

    /**
     * @param $careers
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function saveCareerUser($careers)
    {
        $make_user = ['created_by' => Auth::id(), 'updated_by' => Auth::id()];
        $result = [];
        foreach ($careers as $career) {
            if ($client = $this->clientRepository->firstWhereForeignId($career['client_id'], $career['company_id'])){
                if ($staff_user = $this->staffRepository->getByForeignIdCompanyId($career['staff_user_id'], $career['company_id'])) {
                    $career['client_id'] = $client->id;
                    $career['staff_user_id'] = $staff_user->id;
                    $career['parent_id'] = $career['parent_id'] == 0 ? $career['parent_id'] : $this->careerRepository->getByForeign_idAndCompany_id($career['parent_id'], $career['company_id'])->id;
                    $career['eorder_beg_id'] = is_null($career['eorder_beg_id']) ? $career['eorder_beg_id'] : $this->eOrderRepository->firstForeignIdCompanyId($career['eorder_beg_id'], $career['company_id'])->id;
                    $career['eorder_end_id'] = is_null($career['eorder_end_id']) ? $career['eorder_end_id'] : $this->eOrderRepository->firstForeignIdCompanyId($career['eorder_end_id'], $career['company_id'])->id;
                    $career['release_id'] = is_null($career['release_id']) ? $career['release_id'] : $this->dictiRepository->firstWhereForeignIdCompanyId($career['release_id'], $career['company_id'])->id;
                    $career['empl_type_id'] = is_null($career['empl_type_id']) ? $career['empl_type_id'] : $this->dictiRepository->firstWhereForeignIdCompanyId($career['empl_type_id'], $career['company_id'])->id;
                    $this->careerRepository->create(array_merge($career, $make_user));
                }else{
                    $result[$career['foreign_id']] = ['message' => 'staff_user_id is not found'];
                }
            }else{
                $result[$career['foreign_id']] = ['message' => 'client_id is not found'];
            }
        }
        return $result;
    }


}
