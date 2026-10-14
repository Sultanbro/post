<?php

namespace App\Http\Middleware\Post;

use App\Repository\Post\Comment\CommentRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentMiddleware
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_comment'))abort(403);
        if ($request->route()->getActionMethod() === 'update' or $request->route()->getActionMethod() === 'destroy')
            if ($model = $this->commentRepository->getById($request->route()->parameters['comment']))if ($model->user_id === Auth::id()) {
                return $next($request);
            }else{
                if (!Gate::allows('update_comment'))abort(403);
                if (!Gate::allows('delete_comment'))abort(403);
            }

        return $next($request);
    }
}
