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
        $make_user = ['created_by' => Auth::id(), 'updated_by' => Auth::id()];
        $result = [];
        foreach ($regions as $region) {
            if ($this->regionRepository->firstByForeignIdCompanyId($region['foreign_id'], $region['company_id'])){
                $result[$region['foreign_id']] = ['message' => 'is foreign in base'];
            }elseif ($model = $this->regionRepository->firstByForeignId($region['parent_foreign_id']) or $region['parent_foreign_id'] == 0) {
                $region['parent_id'] = $region['parent_foreign_id'] == 0 ? $region['parent_foreign_id'] : $model->id;
                $region['codes'] = json_encode($region['codes']);
                $this->regionRepository->create(array_merge($region, $make_user));
            }else{
                $result[$region['foreign_id']] = ['message' => 'parent_id is not fond'];
            }
        }
        return $result;
    }
}
