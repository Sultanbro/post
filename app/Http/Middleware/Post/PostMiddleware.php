<?php

namespace App\Http\Middleware\Post;

use App\Repository\Post\PostRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostMiddleware
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getActionMethod() === 'store')if (!Gate::allows('create_post'))abort(403);
        if ($request->route()->getActionMethod() === 'index')if (!Gate::allows('show_post'))abort(403);
        if ($request->route()->getActionMethod() === 'show')if (!Gate::allows('show_post'))abort(403);
        if ($request->route()->getActionMethod() === 'update' or $request->route()->getActionMethod() === 'destroy')
            if ($model = $this->postRepository->firstById($request->route()->parameters['post']))if ($model->user_id === Auth::id()) {
                return $next($request);
            }else{
                if (!Gate::allows('update_post'))abort(403);
                if (!Gate::allows('delete_post'))abort(403);
            }

        return $next($request);
    }
}
