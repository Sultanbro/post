<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Services\Post\PostServiceInterface;
use App\Models\Post\Post;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * PostController constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PostServiceInterface $postService
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(PostRepositoryInterface $postRepository, PostServiceInterface $postService, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository =$departmentRepository;
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
        return PostResource::collection($this->postRepository->getByRoleCompany('show_post'))
            ->additional($this->departmentRepository->getAccessCompany('show_post'));
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
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($this->postRepository->firstByRoleCompany($post, 'show_post'));
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
        if ($this->postRepository->firstById($id)) {
            if ($this->postRepository->update($id, array_merge($request->all(), ['updated_by' => Auth::id(),]))) {
                return response()->json(['message' => 'ok'], 200);
            }
            return response()->json(['message' => 'not save'], 304);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if ($this->postRepository->firstById($id)) {
            if (Storage::disk('local')->exists('public/post_files/' . $id)) {
                Storage::disk('local')->deleteDirectory('public/post_files/' . $id);
            }
            if ($this->postRepository->deleteById($id)) {
                return response()->json(['message' => 'ok'], 200);
            }
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(['message' => 'Not Found'], 404);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getFilter(Request $request)
    {
        return PostResource::collection($this->postRepository->getFilterPosts($request));
    }
}
