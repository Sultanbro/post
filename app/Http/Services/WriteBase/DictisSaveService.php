<?php


namespace App\Http\Services\WriteBase;




use App\Repository\DictiForeignRepositoryInterface;
use App\Repository\DictiRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DictisSaveService implements DictisSaveServiceInterface
{
    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;
    /**
     * @var DictiForeignRepositoryInterface
     */
    private $dictiForeignRepository;

    /**
     * DictisSaveService constructor.
     * @param DictiRepositoryInterface $dictiRepository
     */
    public function __construct(DictiRepositoryInterface $dictiRepository)
    {
        $this->dictiRepository = $dictiRepository;
    }

    public function saveDictis($dictis)
    {
        $user_make = ['created_by' => Auth::id(), 'updated_by'=> Auth::id()];
        foreach ($dictis as $dicti) {
            if (!$this->dictiRepository->compareInNameAndParentId($dicti['full_name'], $dicti['foreign_id']) ) {
                if ($model = $this->dictiRepository->firstWhereForeignIdCompanyId($dicti['parent_foreign_id'], $dicti['company_id'])) {
                    $this->dictiRepository->create(array_merge($dicti, array_merge($user_make, ['parent_id' => $model->id])));
                }
            }
            if ($dicti['parent_foreign_id'] == 0) {
                $this->dictiRepository->create(array_merge($dicti, array_merge($user_make, ['parent_id' => $dicti['parent_foreign_id']])));
            }
            $data[] = $dicti;
        }
        return $data;
    }

}
