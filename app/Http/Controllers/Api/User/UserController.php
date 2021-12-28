<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\CareerResource;
use App\Http\Resources\User\InfoInPeriodResource;
use App\Http\Resources\UserResource;
use App\Http\Services\User\UserServiceInterface;
use App\Models\User;
use App\Repository\Client\EOrder\EOrderRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var EOrderRepositoryInterface
     */
    private $eOrderRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param EOrderRepositoryInterface $EOrderRepository
     * @param UserServiceInterface $userService
     */
    public function __construct(UserRepositoryInterface $userRepository, EOrderRepositoryInterface $EOrderRepository, UserServiceInterface $userService)
    {
        $this->userService = $userService;
        $this->eOrderRepository = $EOrderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserResource($this->userRepository->find(Auth::id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserResource($this->userRepository->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
