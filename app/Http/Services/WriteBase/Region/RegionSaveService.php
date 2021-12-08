<?php


namespace App\Http\Services\WriteBase\Region;


use App\Repository\Reference\City\CityRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Region\RegionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class RegionSaveService implements RegionSaveServiceInterface
{
    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;
    /**
     * @var RegionRepositoryInterface
     */
    private $regionRepository;

    /**
     * DictisSaveService constructor.
     * @param DictiRepositoryInterface $dictiRepository
     * @param CityRepositoryInterface $cityRepository
     * @param RegionRepositoryInterface $regionRepository
     */
    public function __construct(DictiRepositoryInterface $dictiRepository, CityRepositoryInterface $cityRepository, RegionRepositoryInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
        $this->cityRepository = $cityRepository;
        $this->dictiRepository = $dictiRepository;
    }

    /**
     * @param $regions
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function saveRegions($regions)
    {
        foreach ($regions as $region) {
           $region['parent_id'] = $region['parent_foreign_id'] == 0 ? $region['parent_foreign_id'] : $this->regionRepository->firstByForeignId($region['parent_foreign_id'])->id;
           $region['codes'] = json_encode($region['codes']);
           $this->regionRepository->create(array_merge($region, ['created_by' => Auth::id(), 'updated_by' => Auth::id()]));
        }
    }
}
