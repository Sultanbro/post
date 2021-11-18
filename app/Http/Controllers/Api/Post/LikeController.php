<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\LikeStoreRequest;
use App\Http\Requests\Post\LikeInfoRequest;
use App\Http\Resources\LikeResource;
use App\Repository\LikeRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * @var LikeRepositoryInterface
     */
    private $likeReposytory;

    /**
     * LikeController constructor.
     * @param LikeRepositoryInterface $likeRepository
     */
    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeReposytory = $likeRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LikeStoreRequest $request)
    {
        return response()->json($this->likeReposytory->firstOrCreate(array_merge($request->all(), ['user_id' => Auth::id()])));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(LikeInfoRequest $request, $id)
    {
        return LikeResource::collection($this->likeReposytory->byParentIdType($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->likeReposytory->deleteById($id)) {
            return response()->json(['message' => 'ok'], 410);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }
}