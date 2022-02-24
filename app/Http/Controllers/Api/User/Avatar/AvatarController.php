<?php

namespace App\Http\Controllers\Api\User\Avatar;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Avatar\AvatarResource;
use App\Http\Services\WriteBase\Client\ClientBaseService;
use App\Models\Avatar;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use App\Repository\User\Avatar\AvatarRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    /**
     * @var AvatarRepositoryInterface
     */
    private $avatarRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;
    /**
     * @var ClientBaseService
     */
    private $clientBaseService;

    /**
     * AvatarController constructor.
     * @param AvatarRepositoryInterface $avatarRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     * @param ClientBaseService $clientBaseService
     */
    public function __construct(AvatarRepositoryInterface $avatarRepository, DepartmentRepositoryInterface $departmentRepository, ClientBaseService $clientBaseService)
    {
        $this->clientBaseService = $clientBaseService;
        $this->departmentRepository = $departmentRepository;
        $this->avatarRepository = $avatarRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AvatarResource::collection($this->avatarRepository->getByRoleCompany('show_avatar'))
            ->additional($this->departmentRepository->getAccessCompany('show_avatar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client_id = isset($request->client_id) ? $request->client_id : Auth::user()->client_id;
        return $this->clientBaseService->saveAvatar($request->all(), $client_id);
    }

    /**
     * @param Avatar $avatar
     */
    public function show(Avatar $avatar)
    {
        return new AvatarResource($avatar);
    }

    /**
     * @param Request $request
     * @param Avatar $avatar
     * @return bool
     */
    public function update(Request $request, Avatar $avatar)
    {
        return $avatar->update($request->all());
    }

    /**
     * @param Avatar $avatar
     * @return mixed
     */
    public function destroy(Avatar $avatar)
    {
        return $this->clientBaseService->deleteAvatars($avatar);
    }
}
