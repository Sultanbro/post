<?php


namespace App\Http\Services\WriteBase\Duty;


use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Duty\DutyRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DutySaveService implements DutySaveServiceInterface
{
    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var DutyRepositoryInterface
     */
    private $dutyRepository;

    /**
     * DictisSaveService constructor.
     * @param DictiRepositoryInterface $dictiRepository
     * @param DutyRepositoryInterface $dutyRepository
     */
    public function __construct(DictiRepositoryInterface $dictiRepository, DutyRepositoryInterface $dutyRepository)
    {
        $this->dutyRepository = $dutyRepository;
        $this->dictiRepository = $dictiRepository;
    }

    /**
     * @param $duties
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function saveDuty($duties)
    {
        $make_user = ['created_by' => Auth::id(), 'updated_by' => Auth::id()];
        $result = [];
        foreach ($duties as $duty) {
            if (!$this->dutyRepository->getByForeignIdCompanyId($duty['foreign_id'], $duty['company_id'])) {
                if ($position = $this->dictiRepository->firstWhereForeignIdCompanyId($duty['position_id'], $duty['company_id'])){
                    $duty['position_id'] = $position->id;
                    $this->dutyRepository->create(array_merge($duty, $make_user));
                }else{
                    $result[$duty['foreign_id']] = 'position_id is not found';
                }
            }else{
                $result[$duty['foreign_id']] = 'is in base';
            }
        }
        return $result;
    }


}
