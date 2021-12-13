<?php


namespace App\Http\Services\WriteBase\Staff;

use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\User\Staff\StaffUserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class StaffSaveService implements StaffSaveServiceInterface
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
     * StaffSaveService constructor.
     * @param StaffUserRepositoryInterface $staffUserRepository
     * @param EOrderRepositoryInterface $eOrderRepository
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(StaffUserRepositoryInterface $staffUserRepository, EOrderRepositoryInterface $eOrderRepository, ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->eOrderRepository = $eOrderRepository;
        $this->staffRepository = $staffUserRepository;
    }

    /**
     * @param $staffs
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function saveStaff($staffs)
    {
        $make_user = ['crated_by' => Auth::id(), 'updated_by' => Auth::id()];
        $result = [];
        foreach ($staffs as $staff) {
            if (!$this->staffRepository->getByForeignIdCompanyId($staff['foreign_id'], $staff['company_id'])) {
                if ($department = $this->clientRepository->firstWhereForeignId($staff['foreign_id'], $staff['department_id'])) {
                    $staff['department_id'] = $department;
                    $staff['eorder_beg_id'] = is_null($staff['eorder_beg_id']) ? $staff['eorder_beg_id'] : $this->eOrderRepository->firstForeignIdCompanyId($staff['eorder_beg_id'], $staff['company_id']);
                    $staff['eorder_end_id'] = is_null($staff['eorder_end_id']) ? $staff['eorder_end_id'] : $this->eOrderRepository->firstForeignIdCompanyId($staff['eorder_end_id'], $staff['company_id']);
                    $this->staffRepository->create(array_merge($staff, $make_user));
                }else{
                    $result[$staff['foreign_id']] = ['message' => 'not found department id'];
                }
            }else{
                $result[$staff['foreign_id']] = ['message' => 'is in base'];
            }
        }
        return request()->json($result);
    }


}
