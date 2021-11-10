<?php

namespace App\Http\Services\Post;

use App\Repository\PostFileRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Exception;

class PostService implements PostServiceInterface
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var PostRepositoryInterface
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
        $model = $this->postRepository->create(array_merge($req['content'], $req['company_id'], $req['group_id'], ['user_id' => $user_id, 'created_by' => $user_id, 'updated_by' => $user_id]));

        if ($model && $req['postFiles']) {
            $results = $this->saveFiles($req['postFiles'], $model->id);
            if (isset($results['success'])) {
                $this->postRepository->deleteById($model->id);
                return response()->json($results, 417);
            }
            foreach ($results as $result) {
                $this->postFileRepo->create(['post_id' => $model->id, 'link' => $result]);
            }
            return response()->json($model, 200);
        }
    }

    /**
     * @param array $params
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function saveFiles(array $params, int $id)
    {
        try {
                foreach ($params as $name => $files) {
                    foreach ($files as $file) {
                        $fileName = $file->getClientOriginalName();
                        $content = file_get_contents($file->getRealPath());
                        $link[] = Storage::disk('local')->put("public/post_files/$id/$name/$fileName", $content);
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
