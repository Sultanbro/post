<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\LikeStoreRequest;
use App\Http\Requests\Post\LikeInfoRequest;
use App\Http\Resources\Post\LikeResource;
use App\Repository\Post\Like\LikeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use function Composer\Autoload\includeFile;

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
        if ($model = $this->likeReposytory->firstByPostId(array_merge($request->all(), ['user_id' => Auth::id()]))) {
            if ($this->likeReposytory->deleteById($model->id)) {
                return ['like_count' => $this->likeReposytory->liked_count($model->parent_id, $model->type)];
            }
            return response()->json(['message' => 'Not Found'], 404);
        }
        if ($model = $this->likeReposytory->firstOrCreate(array_merge($request->all(), ['user_id' => Auth::id()]))) {
            return ['like_count' => $this->likeReposytory->liked_count($model->parent_id, $model->type)];
        }
        return response()->json(['message' => 'not found'], 404);
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

}
