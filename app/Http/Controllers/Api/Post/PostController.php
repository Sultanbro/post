<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Services\Post\PostServiceInterface;
use App\Models\Post;
use App\Repository\PostRepositoryInterface;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class PostController extends Controller
{

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var PostServiceInterface
     */
    private $postService;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PostServiceInterface $postService
     */
    public function __construct(PostRepositoryInterface $postRepository, PostServiceInterface $postService)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if (Auth::user()->token->role_id == 1) {
            return PostResource::collection($this->postRepository->getPostsWithData());
        }
        return PostResource::collection($this->postRepository->getPostsByCompanyId([Auth::user()->company_id, 2]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostStoreRequest $request)
    {
        $user_id = Auth::id();

        return $this->postService->store($request->all(), $user_id);
    }

    /**
     * @param Post $post
     * @return mixed
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, $id)
    {
        $post = $this->postRepository->find($id);
        if ($post->user_id == Auth::id() or Auth::user()->token->role_id == 1) {
            if ($this->postRepository->update($id, array_merge($request->all(), ['updated_by' => Auth::id(),]))) {
                return response()->json(['message' => 'ok'], 200);
            }
            return response()->json(['message' => 'not save'], 304);
        }
        return response()->json(['message' => 'Forbidden'], 403);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = $this->postRepository->find($id);
        if ($post->user_id == Auth::id() or Auth::user()->token->role_id == 1) {
            if (Storage::disk('local')->exists('public/post_files/' . $id)) {
                Storage::disk('local')->deleteDirectory('public/post_files/' . $id);
            }
            if ($this->postRepository->deleteById($id)) {
                return response()->json(['message' => 'ok'], 200);
            }
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(['message' => 'Forbidden'], 403);

    }
}
