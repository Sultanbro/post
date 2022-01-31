<?php

namespace App\Http\Controllers\Api\Email;

use App\Http\Controllers\Controller;
use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Http\Services\Email\EmailServiceInterface;
use App\Jobs\SendEmailJob;
use App\Repository\Email\EmailDomainRepository;
use App\Repository\Email\EmailDomainRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @var EmailDomainRepositoryInterface
     */
    private $emailDomainRepository;

    /**
     * EmailController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserAuthServiceInterface $userAuthService
     * @param EmailServiceInterface $emailService
     * @param EmailDomainRepositoryInterface $emailDomainRepository
     */
    public function __construct(UserRepositoryInterface $userRepository,
                                UserAuthServiceInterface $userAuthService,
                                EmailServiceInterface $emailService,
                                EmailDomainRepositoryInterface $emailDomainRepository)
    {
        $this->emailDomainRepository = $emailDomainRepository;
        $this->emailService = $emailService;
        $this->userAuthService = $userAuthService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendResetPasswordEmail(Request $request)
    {
        return $this->emailService->sendResetPasswordEmail($request->all());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveFile(Request $request)
    {
        return $this->emailService->saveFile($request->file());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function saveEmailDomain(Request $request)
    {
        $make_user = ['created_by' => Auth::id(), 'updated_by' => Auth::id(),];
        return $this->emailDomainRepository->create(array_merge($request->all(), $make_user));
    }
}
