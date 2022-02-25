<?php

namespace App\Http\Services\Post;

use App\Http\Resources\Post\PostResource;
use App\Repository\Post\Comment\CommentRepositoryInterface;
use App\Repository\Post\PostFile\PostFileRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var PostFileRepositoryInterface
     */
    private $postFileRepo;
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * PostService constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PostFileRepositoryInterface $postFileRepository
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(PostRepositoryInterface $postRepository, PostFileRepositoryInterface $postFileRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->postFileRepo = $postFileRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param $req
     * @param $user_id
     * @return mixed|void
     */
    public function store($req, $user_id)
    {
        $model = $this->postRepository->create(array_merge($req, [ 'user_id' => $user_id, 'created_by' => $user_id, 'updated_by' => $user_id]));

        if ($model && isset($req['postFiles'])) {
            $results = $this->saveFiles($req['postFiles'], $model->id);
            if (isset($results['success'])) {
                $this->postRepository->deleteById($model->id);
                return response()->json($results, 417);
            }
            foreach ($results as $result) {
                $this->postFileRepo->create(['post_id' => $model->id, 'link' => $result, 'created_by' => $user_id, 'updated_by' => $user_id]);
            }
        }
        return new PostResource($model);
    }

    /**
     * @param array $params
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function saveFiles(array $params, int $id)
    {
        $link = [];
        try {
                foreach ($params as $name => $files) {
                    foreach ($files as $file) {
                        $fileName = $file->getClientOriginalName();
                        $content = file_get_contents($file->getRealPath());
                        if (Storage::disk('local')->put("public/post_files/$id/$name/$fileName", $content)) {
                            $link[] = "post_files/$id/$name/$fileName";
                        }
                    }
                }
            return $link;
        }catch (Exception $e) {
            $error = $e->getMessage();
            $success = false;
            return [
                'success' => $success,
                'error'   => $error
            ];
        }
    }

    /**
     * @param $req
     * @param $id
     * @return mixed|void
     */
    public function saveCommentFile($req, $id)
    {
            $fileName = $req['file']->getClientOriginalName();
            $content = file_get_contents($req['file']->getRealPath());
            $link = "public/comments/$id/$fileName";
            Storage::disk('local')->put($link, $content);
            return $link;
    }


    /**
     * @inheritDoc
     */
    public function deleteComment($id)
    {
        if ($model = $this->commentRepository->getById($id)) {
                $child_comments = $this->commentRepository->getChildCommentById($id);
                if (count($child_comments) >= 1) {
                    foreach ($child_comments as $child_comment) {
                        $this->deleteCommentsStore($child_comment->id);
                    }
                }
                if ($this->commentRepository->deleteByParentId($id)) {
                    $this->deleteCommentsStore($id);
                    if ($this->commentRepository->deleteById($id)) {
                        return response()->json(['post_id' => $model->post_id, 'comment_count' => $this->commentRepository->getAllCommentsByPostId($model->post_id)->count()], 200);
                    }
                }
                return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }

    /**
     * @inheritDoc
     */
    public function deleteCommentsStore($id)
    {
        if (Storage::disk('local')->exists("public/comments/$id")) {
            Storage::disk('local')->deleteDirectory("public/comments/$id");
        }
    }
}
