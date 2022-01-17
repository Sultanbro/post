<?php

namespace App\Http\Controllers\Api\Email;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * EmailController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     */
    public function sendEmail(Request $request)
    {
        $result = [];
        if ($request->user == 'all') {
            $users = $this->userRepository->all();
        }elseif (is_array($request->user)) {
            foreach ($request->user as $user) {
                if ($userModel = $this->userRepository->userById($user)) {
                    $users[] = $userModel;
                }else{
                    $result[$user] = 'this not found';
                }
            }
        }

        $details = $request->all();

        if (isset($users)) {
            foreach ($users as $user) {
                $details['email'] = $user->email;
                dispatch(new SendEmailJob($details));
            }
            return 'ok';
        }else{
            return $result;
        }

    }
}
