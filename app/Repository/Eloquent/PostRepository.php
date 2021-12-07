<?php


namespace App\Repository\Eloquent;


use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    /**
     * UserRepository constructor.
     * @param Post $model
     */
    public function __construct(Post $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $company_id
     * @return \Ramsey\Collection\Collection
     */
    public function getPostsByCompanyId($company_id)
    {
        return $this->model->whereIn('company_id', $company_id)->get();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPostsWithData()
    {
        return $this->model->with([
            'commentsLimit'
            => function(HasMany $hasMany){
                $hasMany->with([
                    'comment' => function(HasMany $comment){
                    $comment->with('comment' )
                        ->with( 'countLike')
                        ->with('commentUser')->limit(0)->withCount('liked');
                },
                    'countLike', 'commentUser'])->withCount('liked');
            },
        'like', 'postFiles', 'postsUser'])->latest()->withCount('liked')->withCount('allComments')->paginate(6);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByPostId($id)
    {
        return $this->model->where('id', $id)->get();
    }
}
