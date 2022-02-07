<?php


namespace App\Http\Services\Centcoin;

use App\Http\Resources\Centcoin\CentcoinResource;
use App\Models\Centcoin\Centcoin;
use App\Models\Centcoin\CentcoinApply;
use App\Repository\Centcoin\CentcoinApplyRepositoryInterface;
use App\Repository\Centcoin\CentcoinRepositoryInterface;
use Illuminate\Support\Facades\Auth;


class CentcoinService implements CentcoinServiceInterface
{

    /**
     * @var CentcoinRepositoryInterface
     */
    private $centcoinRepository;
    private $centcoinApply;

    /**
     * @param CentcoinRepositoryInterface $centcoinRepository
     * @param CentcoinApplyRepositoryInterface $centcoinApply
     */
    public function __construct(CentcoinRepositoryInterface $centcoinRepository, CentcoinApplyRepositoryInterface $centcoinApply)
    {
        $this->centcoinRepository = $centcoinRepository;
        $this->centcoinApply = $centcoinApply;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function show($id)
    {
       return $this->centcoinRepository->where('user_id','=',$id);
    }

    /**
     * @param $request
     * @param $created_id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function centcoinApply($request, $created_id)
    {
        $lastOperation =  $this->centcoinRepository->where('user_id','=', $request['user_id']);
        if(is_null($lastOperation)){
            return response()->json(['error' => 'This user not found'], 403);
        } else if ($lastOperation->total >= $request['total']) {
            $apply = new CentcoinApply();
            $apply->type_id = $request['type_id'];
            $apply->total = $request['total'] * $request['quantity'];
            $apply->quantity = $request['quantity'];
            $apply->user_id = $request['user_id'];
            $apply->updated_by = $created_id;
            $apply->created_by = $created_id;
            $apply->save();
            return response()->json(['message' => 'Application created', 'success' => true], 200);
        } else if($lastOperation->total <= $request['total']){
            return response()->json(['error' => 'Not enough centcoins'], 403);
        }
    }

    // Для Админки

    public function index()
    {
        return CentcoinResource::collection($this->centcoinRepository->all());
    }

    /**
     * @param $request
     * @param $created_id
     * @return CentcoinResource
     */
    public function store($request, $created_id)
    {
        $user = $this->centcoinRepository->firstOrCreate(array_merge($request,[
            'type_id' => 'регистрация',
            'total' => ($request['quantity'] > 0) ?: 0,
            'updated_by' => $created_id,
            'created_by' => $created_id
        ]));

        return new CentcoinResource($user);
    }

    /**
     * @param $request
     * @param $created_id
     * @return CentcoinResource|\Illuminate\Http\JsonResponse|mixed
     */
    public function operationCoins($request, $created_id)
    {
        $lastOperation =  $this->centcoinRepository->where('user_id','=', $request['user_id']);
        if(is_null($lastOperation)){
            return $this->store($request,$created_id);
        }else {
            $newOperation = new Centcoin();
            $newOperation->type_id = $request['type_id'];
            $newOperation->description = $request['description'];
            $newOperation->quantity = $request['quantity'];
            $newOperation->total = (($lastOperation->total + $request['quantity']) > 0) ? ($lastOperation->total + $request['quantity']) : 0;
            $newOperation->user_id = $request['user_id'];
            $newOperation->updated_by = $created_id;
            $newOperation->created_by = $created_id;
            $newOperation->save();
            return response()->json(['message' => $newOperation->type_id, 'success' => true], 200);
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function statusApply($request)
    {
        $user = Auth::id();
        $apply = $this->centcoinApply->find($request['id']);
        if(!is_null($apply)){
            if($request['status'] == 'Исполнено' && $apply->update($request)) {
                $lastOperation =  $this->centcoinRepository->where('user_id','=', $apply->user_id);
                $newOperation = new Centcoin();
                $newOperation->type_id = 'Покупка';
                $newOperation->description = $apply->type_id;
                $newOperation->quantity = $apply->total;
                $newOperation->total = $lastOperation->total - $apply->total;
                $newOperation->user_id = $apply->user_id;
                $newOperation->updated_by = $user;
                $newOperation->created_by = $user;
                $newOperation->save();

                return response()->json(['message' => 'Status updated','success' => true],200);
            }else {
                return response()->json(['message' => 'No product','success' => $apply->update($request)],403);
            }
        } else {
            return response()->json(['error' => 'This request does not exist'],404);
        }
    }
}
