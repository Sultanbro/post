<?php

namespace App\Http\Controllers\Api\WriteBase;

use App\Http\Controllers\Controller;
use App\Repository\User\Career\CareerUserRepositoryInterface;
use Illuminate\Http\Request;

class CareerUserController extends Controller
{
    /**
     * @var CareerUserRepositoryInterface
     */
    private $careerRepository;

    /**
     * CareerUserController constructor.
     * @param CareerUserRepositoryInterface $careerUserRepository
     */
    public function __construct(CareerUserRepositoryInterface $careerUserRepository)
    {
        $this->careerRepository = $careerUserRepository;
    }

    public function saveCareer(Request $request)
    {
//        return
    }
}
