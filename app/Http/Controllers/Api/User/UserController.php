<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return new UserResource($this->userRepository->find(Auth::id()));
        if (Auth::id() == 3) {
            return ['mail' => 'master@mail.uz',
                'position' => 'Ведущий Web-программист',
                'education' => 'Казахский Национальный технический университет им. К.Сатпаева',
                'city' => 'Нукус',
                'b_day' => '30.03.1995',
                'sick' => [0 => ['day' => '3', 'dates' => '14.10.2021-16.10.2021'],
                            1 => ['day' => '7', 'period' => '15.11.2021-22.11.2021']],
                'vacation' => [0 => ['type' => 'Ежегодный основной оплачиваемый отпуск',
                                     'period' => '01.05.2021-31.04.2022',
                                     'periodvac' => [0 => '11.09.2021-15.09.2021', 1 => '01.12.2021-05.12.2021'],
                                     'duration' => '10', 'rest' => '18']],
                'career' => [0 => ['datebeg' => '01.05.2021', 'dateend' => '01.08.2021',
                                    'department' => 'Управление web-программирования', 'duty' => 'Web - программист'],
                            1 => ['datebeg' => '01.08.2021', 'dateend' => '0',
                                    'department' => 'Управление web-программирования', 'duty' => 'Ведущий Web-программист']]];
        }
        if (Auth::id() == 29) {
            return ['mail' => 'test@mail.ru',
                'position' => 'Web-программист',
                'education' => 'МУИТ',
                'city' => 'Караганда',
                'b_day' => '22.10.1988',
                'sick' => [0 => ['day' => '5', 'dates' => '08.10.2021-12.10.2021']],
                'career' => [0 => ['datebeg' => '11.09.2021', 'dateend' => '0',
                        'department' => 'Управление web-программирования', 'duty' => 'Web-программист']]];
        }
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
