<?php

namespace App\Http\Controllers\Api\Centcoin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Centcoin\CentcoinApplyStoreRequest;
use App\Http\Resources\Centcoin\CentcoinApplyResource;
use App\Models\CentcoinApply;
use App\Repository\CentcoinApplyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentcoinApplyController extends Controller
{

    /**
     * @var CentcoinApplyRepositoryInterface
     */
    private $centcoinApplyRepository;

    /**
     * CentcoinApplyController constructor.
     * @param CentcoinApplyRepositoryInterface $centcoinApplyRepository
     */
    public function __construct(CentcoinApplyRepositoryInterface $centcoinApplyRepository)
    {
        $this->centcoinApplyRepository = $centcoinApplyRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CentcoinApplyResource::collection($this->centcoinApplyRepository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CentcoinApplyResource
     */
    public function store(CentcoinApplyStoreRequest $request)
    {
        $user = Auth::id();
        return new CentcoinApplyResource($this->centcoinApplyRepository->create(array_merge($request->validated(), ['created_by' => $user, 'updated_by' => $user])));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return CentcoinApplyResource
     */
    public function show($id)
    {
        return new CentcoinApplyResource($this->centcoinApplyRepository->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
