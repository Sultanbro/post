<?php

namespace App\Http\Controllers\Api\Email;

use App\Http\Controllers\Controller;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Http\Services\Email\EmailServiceInterface;
use App\Jobs\SendEmailJob;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    //
    /**
     * @var UserAuthServiceInterface
     */
    private $userAuthService;
    /**
     * @var EmailServiceInterface
     */
    private $emailService;

    /**
     * EmailController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserAuthServiceInterface $userAuthService
     * @param EmailServiceInterface $emailService
     */
    public function __construct(UserRepositoryInterface $userRepository, UserAuthServiceInterface $userAuthService, EmailServiceInterface $emailService)
    {
        $this->emailService = $emailService;
        $this->userAuthService = $userAuthService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     */
    public function sendResetPasswordEmail(Request $request)
    {
        return $this->emailService->sendResetPasswordEmail($request->all());
    }

    public function saveFile(Request $request)
    {
        return $this->emailService->saveFile($request->file());
    }
}
