<?php

namespace App\Http\Controllers\Api\Centcoin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\Api\Centcoin\CentcoinStoreRequest;
use App\Http\Resources\Centcoin\CentcoinResource;
use App\Models\Centcoin;
use App\Repository\CentcoinRepositoryInterface;
use Illuminate\Http\Request;
use App\Repository\Eloquent\CentcoinRepository;
use Illuminate\Support\Facades\Auth;

class CentcoinController extends Controller
{

    /**
     * @var CentcoinRepositoryInterface
     */
    private $centcoinRepository;

    /**
     * CentcoinController constructor.
     * @param CentcoinRepositoryInterface $centcoinRepository
     */
    public function __construct(CentcoinRepositoryInterface $centcoinRepository)
    {
        $this->centcoinRepository = $centcoinRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CentcoinResource::collection($this->centcoinRepository->all());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
* //     * @param $user_id
     * @return CentcoinResource
     */
    public function store(CentcoinStoreRequest $request)
    {
        $user = Auth::id();
        return new CentcoinResource($this->centcoinRepository->create(array_merge($request->validated(), ['created_by' => $user, 'updated_by' => $user])));


    }

    /**
     * Display the specified resource.
     *

     * @param  int  $id
     * @return CentcoinResource
     */
    public function show($id)
    {
        return new CentcoinResource($this->centcoinRepository->find($id));
    }
}
