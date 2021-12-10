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

    public function saveDuty($duties)
    {
        foreach ($duties as $duty) {
//            if ($this->dutyRepository->)
        }
    }


}
