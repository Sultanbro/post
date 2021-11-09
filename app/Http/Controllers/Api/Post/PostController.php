<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repository\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        if (Auth::user()->token->role_id == 1) {
            return PostResource::collection($this->postRepository->all());
        }
        return PostResource::collection($this->postRepository->getPostsByCompanyId([Auth::user()->company_id, 2]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $user_id = Auth::id();
        return response()->json($this->postRepository->create(array_merge($request->all(), ['user_id' => $user_id, 'created_by' => $user_id, 'updated_by' => $user_id])));
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
            if ($this->postRepository->deleteById($id)) {
                return response()->json(['message' => 'ok'], 410);
            }
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(['message' => 'Forbidden'], 403);

    }
}
