<?php


namespace App\Http\Services\Centcoin;

use App\Http\Resources\Centcoin\CentcoinResource;
use App\Models\Centcoin\Centcoin;
use App\Repository\Centcoin\CentcoinApplyRepositoryInterface;
use App\Repository\Centcoin\CentcoinRepositoryInterface;
use Illuminate\Support\Facades\Auth;


class CentcoinService implements CentcoinServiceInterface
{

    /**
     * @var CentcoinRepositoryInterface
     */
    private $centcoinRepository;
    private $centcoinApplyRepository;

    /**
     * @param CentcoinRepositoryInterface $centcoinRepository
     * @param CentcoinApplyRepositoryInterface $centcoinApplyRepository
     */
    public function __construct(CentcoinRepositoryInterface $centcoinRepository, CentcoinApplyRepositoryInterface $centcoinApplyRepository)
    {
        $this->centcoinRepository = $centcoinRepository;
        $this->centcoinApplyRepository = $centcoinApplyRepository;
    }

    /**
     * @param $request
     * @param $created_id
     * @return CentcoinResource|\Illuminate\Http\JsonResponse
     */
    public function transaction($request, $created_id)
    {
            $lastOperation =  $this->centcoinRepository->where('user_id','=', $request['user_id']);
            $total = $lastOperation ? $lastOperation->total:0;
                $user = $this->centcoinRepository->create(array_merge($request,[
                    'total' => (($total + $request['quantity']) > 0) ? ($total + $request['quantity']) : 0,
                    'updated_by' => $created_id,
                    'created_by' => $created_id
                ]));
                return response()->json(['success' => true,'data' => new CentcoinResource($user)], 200);
    }

    public function applyOperation($request,$created_id)
    {
        $lastOperation =  $this->centcoinRepository->where('user_id','=', $request['user_id']);
        $total = $lastOperation ? $lastOperation->total:0;
        if ($total >= $request['total']) {
            $this->centcoinApplyRepository->create(array_merge($request,[
                'total' => $request['total'] * $request['quantity'],
                'updated_by' => $created_id,
                'created_by' => $created_id
            ]));
            return response()->json(['message' => 'Application created', 'success' => true], 200);
        } else if($total <= $request['total']){
            return response()->json(['error' => 'Not enough centcoins'], 403);
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function statusApply($request)
    {
        $user = Auth::id();
        $apply = $this->centcoinApplyRepository->find($request['id']);

        if(!is_null($apply)) {
            if (empty($request['status'])) {
                return response()->json(['message' => 'Apply updated','success' => $apply->update($request)],200);

            } elseif ($request['status'] === 'Отказано') {
                $apply->update($request);
                return response()->json(['message' => 'Status denied','success' => $apply->update($request)],200);

            } elseif ($request['status'] === 'Исполнено') {
                $lastOperation = $this->centcoinRepository->where('user_id', '=', $apply->user_id);
                $newOperation = new Centcoin();
                $newOperation->type_id = 'Покупка';
                $newOperation->description = $apply->type_id;
                $newOperation->quantity = $apply->total;
                $newOperation->total = $lastOperation->total - $apply->total;
                $newOperation->user_id = $apply->user_id;
                $newOperation->updated_by = $user;
                $newOperation->created_by = $user;
                $newOperation->save();

                return response()->json(['message' => 'Status success updated','success' => true],200);
            }
        } else {
            return response()->json(['error' => 'This request does not exist'],404);
        }
    }
}
