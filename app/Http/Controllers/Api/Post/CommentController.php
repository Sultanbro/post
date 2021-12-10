<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CommentStoreRequest;
use App\Http\Requests\Post\CommentUpdateRequest;
use App\Http\Resources\Post\CommentResource;
use App\Repository\Post\Comment\CommentRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * CommentController constructor.
     * @param CommentRepositoryInterface $commentRepository
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository, PostRepositoryInterface $postRepository)
    {
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
        return new CommentResource($this->commentRepository->create(array_merge($request->all(),['user_id' => Auth::id(), 'created_by' => Auth::id(), 'updated_by' => Auth::id(),])));
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
        if (Auth::id() === $this->commentRepository->find($id)->user_id) {
            if ($this->commentRepository->update($id,  $request->all())) {
                return new CommentResource($this->commentRepository->find($id));
            }
            return response()->json(['message' => 'not save'], 304);
        }
        return response()->json(['message' => 'Forbidden '], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($model = $this->commentRepository->getById($id)) {
            if (Auth::id() === $model->user_id) {
                if ($this->commentRepository->deleteByParentId($id)) {
                    if ($this->commentRepository->deleteById($id)) {
                        $post = $this->postRepository->find($model->post_id);
                        return response()->json(['post_id' => $model->post_id, 'comment_count' => $this->commentRepository->getAllCommentsByPostId($model->post_id)->count()], 200);
                    }
                }
                return response()->json(['message' => 'Not Found'], 404);
            }
        }
        return response()->json(['message' => 'Forbidden '], 403);
    }

}
