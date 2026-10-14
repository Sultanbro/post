<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Http\Services\WriteBase\CareerUser\CareerUserSaveServiceInterface;
use App\Repository\User\Career\CareerUserRepositoryInterface;
use Illuminate\Http\Request;

class CareerUserController extends Controller
{
    /**
     * @var CareerUserRepositoryInterface
     */
    private $careerRepository;
    /**
     * @var CareerUserSaveServiceInterface
     */
    private $careerService;

    /**
     * CareerUserController constructor.
     * @param CareerUserRepositoryInterface $careerUserRepository
     * @param CareerUserSaveServiceInterface $careerService
     */
    public function __construct(CareerUserRepositoryInterface $careerUserRepository, CareerUserSaveServiceInterface $careerService)
    {
        $this->careerService = $careerService;
        $this->careerRepository = $careerUserRepository;
    }

    public function saveCareer(Request $request)
    {
        return $this->careerService->saveCareerUser($request->all());
    }
}
