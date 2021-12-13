<?php

namespace App\Http\Services\Post;

use App\Http\Resources\Post\PostResource;
use App\Repository\Post\PostFile\PostFileRepositoryInterface;
use App\Repository\Post\PostRepositoryInterface;
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
     * PostService constructor.
     * @param PostRepositoryInterface $postRepository
     * @param PostFileRepositoryInterface $postFileRepository
     */
    public function __construct(PostRepositoryInterface $postRepository, PostFileRepositoryInterface $postFileRepository)
    {
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
        $model = $this->postRepository->create(array_merge(['content' => $req['content'], 'company_id' => $req['company_id'], 'group_id' => $req['group_id'], 'user_id' => $user_id, 'created_by' => $user_id, 'updated_by' => $user_id]));

        if ($model && isset($req['postFiles'])) {
            $results = $this->saveFiles($req['postFiles'], $model->id);
            if (isset($results['success'])) {
                $this->postRepository->deleteById($model->id);
                return response()->json($results, 417);
            }
            foreach ($results as $result) {
                $this->postFileRepo->create(['post_id' => $model->id, 'link' => $result]);
            }
            return new PostResource($model);
        }
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
}
