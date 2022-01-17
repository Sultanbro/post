<?php

namespace App\Providers;


use App\Repository\Client\ClientContact\ClientContactRepository;
use App\Repository\Client\ClientContact\ClientContactRepositoryInterface;
use App\Repository\Client\ClientRepository;
use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepository;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\Client\EOrder\EOrderRepository;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\EloquentRepositoryInterface;
use App\Repository\Post\Comment\CommentRepository;
use App\Repository\Post\Comment\CommentRepositoryInterface;
use App\Repository\Post\Like\LikeRepository;
use App\Repository\Post\Like\LikeRepositoryInterface;
use App\Repository\Post\PostFile\PostFileRepository;
use App\Repository\Post\PostFile\PostFileRepositoryInterface;
use App\Repository\Post\PostRepository;
use App\Repository\Post\PostRepositoryInterface;
use App\Repository\Reference\City\CityRepository;
use App\Repository\Reference\City\CityRepositoryInterface;
use App\Repository\Reference\Dicti\DictiRepository;
use App\Repository\Reference\Dicti\DictiRepositoryInterface;
use App\Repository\Reference\Duty\DutyRepository;
use App\Repository\Reference\Duty\DutyRepositoryInterface;
use App\Repository\Reference\Region\RegionRepository;
use App\Repository\Reference\Region\RegionRepositoryInterface;
use App\Repository\User\Career\CareerUserRepository;
use App\Repository\User\Career\CareerUserRepositoryInterface;
use App\Repository\User\Employee\EmployeeRepository;
use App\Repository\User\Employee\EmployeeRepositoryInterface;
use App\Repository\User\Staff\StaffUserRepository;
use App\Repository\User\Staff\StaffUserRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\User\UserTokenRepository;
use App\Repository\User\UserTokenRepositoryInterface;
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
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(ClientContactRepositoryInterface::class, ClientContactRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(EOrderRepositoryInterface::class, EOrderRepository::class);
        $this->app->bind(DutyRepositoryInterface::class, DutyRepository::class);
        $this->app->bind(CareerUserRepositoryInterface::class, CareerUserRepository::class);
        $this->app->bind(StaffUserRepositoryInterface::class, StaffUserRepository::class);
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
