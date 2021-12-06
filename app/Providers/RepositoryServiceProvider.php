<?php

namespace App\Providers;

use App\Repository\CentcoinApplyRepositoryInterface;
use App\Repository\CentcoinRepositoryInterface;
use App\Repository\ClientRepositoryInterface;
use App\Repository\CommentRepositoryInterface;
use App\Repository\DepartmentRepositoryInterface;
use App\Repository\DictiForeignRepositoryInterface;
use App\Repository\DictiRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\CentcoinApplyRepository;
use App\Repository\Eloquent\CentcoinRepository;
use App\Repository\Eloquent\ClientRepository;
use App\Repository\Eloquent\CommentRepository;
use App\Repository\Eloquent\DepartmentRepository;
use App\Repository\Eloquent\DictiForeignRepository;
use App\Repository\Eloquent\DictiRepository;
use App\Repository\Eloquent\LikeRepository;
use App\Repository\Eloquent\PostFileRepository;
use App\Repository\Eloquent\PostRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\UserTokenRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\LikeRepositoryInterface;
use App\Repository\PostFileRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\UserTokenRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserTokenRepositoryInterface::class, UserTokenRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostFileRepositoryInterface::class, PostFileRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(LikeRepositoryInterface::class, LikeRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(DictiRepositoryInterface::class, DictiRepository::class);
        $this->app->bind(CentcoinRepositoryInterface::class, CentcoinRepository::class);
        $this->app->bind(CentcoinApplyRepositoryInterface::class, CentcoinApplyRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
