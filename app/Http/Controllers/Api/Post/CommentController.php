<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CommentStoreRequest;
use App\Http\Requests\Post\CommentUpdateRequest;
use App\Http\Resources\Post\CommentResource;
use App\Http\Services\Post\PostServiceInterface;
use App\Repository\Post\Comment\CommentRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var PostServiceInterface
     */
    private $postService;

    /**
     * CommentController constructor.
     * @param CommentRepositoryInterface $commentRepository
     * @param PostRepositoryInterface $postRepository
     * @param PostServiceInterface $postService
     */
    public function __construct(CommentRepositoryInterface $commentRepository, PostRepositoryInterface $postRepository, PostServiceInterface $postService)
    {
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CommentResource::collection($this->commentRepository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentStoreRequest $request)
    {
        try {
            $model = $this->commentRepository->create(array_merge($request->all(), ['user_id' => Auth::id(), 'created_by' => Auth::id(), 'updated_by' => Auth::id(),]));
            if ($request->has('file')) {
                $this->commentRepository->update($model->id, ['link' => $this->postService->saveCommentFile($request, $model->id)]);
            }
            return new CommentResource($this->commentRepository->find($model->id));
        }catch (\Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        return CommentResource::collection($this->commentRepository->getCommentsByPostId($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommentUpdateRequest $request, $id)
    {
        if ($this->commentRepository->getById($id)) {
            if ($this->commentRepository->update($id,  $request->all())) {
                return new CommentResource($this->commentRepository->find($id));
            }
            return response()->json(['message' => 'not save'], 304);
        }
        return response()->json(['message' => 'Not Found '], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->postService->deleteComment($id);
    }

}
