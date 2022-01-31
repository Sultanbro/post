<?php


namespace App\Http\Services\WriteBase\Dicti;


use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DictisSaveService implements DictisSaveServiceInterface
{
    /**
     * @var DictiRepositoryInterface
     */
    private $dictiRepository;

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
        $data = [];
        foreach ($dictis as $dicti) {
            if (!$this->dictiRepository->firstWhereForeignIdCompanyId($dicti['foreign_id'], $dicti['company_id']) ) {
                if ($model = $this->dictiRepository->firstWhereForeignIdCompanyId($dicti['parent_foreign_id'], $dicti['company_id'])) {
                    $user_make['parent_id'] = $model->id;
                    $this->dictiRepository->create(array_merge($dicti, $user_make));
                    $data[$dicti['foreign_id']] = ['message' => 'ok'];
                } elseif ($dicti['parent_foreign_id'] == 0){
                    $user_make['parent_id'] = $dicti['parent_foreign_id'];
                    $this->dictiRepository->create(array_merge($dicti, $user_make));
                    $data[$dicti['foreign_id']] = ['message' => 'ok'];
                }else{
                    $data[$dicti['foreign_id']] = ['message' => 'no parent id'];
                }
            }else {
                $data[$dicti['foreign_id']] = ['message' => 'dicti is in base'];
            }
        }
        return $data;
    }

}
