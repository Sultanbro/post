<?php


namespace App\Http\Services\WriteBase\City;


use App\Repository\Reference\City\CityRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Region\RegionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CitiesSaveService implements CitiesSaveServiceInterface
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

    public function saveCities($cities)
    {

        foreach ($cities as $city) {
           if ($region = $this->regionRepository->firstByForeignId($city['region_id'])) {
            if ($country = $this->dictiRepository->firstWhereForeignIdCompanyId($city['country_id'], $city['company_id'])) {
                $city['codes'] = json_encode($city['codes']);
                $city['region_id'] = $region->id;
                $city['country_id'] = $country->id;
                $city['parent_id'] = is_null($city['parent_id']) ? null : $this->cityRepository->firstForeignCompanyId($city['parent_id'], $city['company_id']);
                if ($this->cityRepository->create(array_merge($city, ['created_by' => Auth::id(), 'updated_by' => Auth::id()]))) {
                    continue;
                }
                $city['message'] = 'no dicti';
                $result[] = $city;
            }
               $city['message'] = 'no country_id';
               $result[] = $city;
           }
        }
        return response()->json($result = isset($result[0]) ? $result : ['message' => 'ok']);
    }
}
