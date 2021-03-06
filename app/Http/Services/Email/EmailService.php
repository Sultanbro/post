<?php


namespace App\Http\Services\Email;


use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Jobs\SendEmailJob;
use App\Repository\Email\EmailDomainRepository;
use App\Repository\Email\EmailDomainRepositoryInterface;
use App\Repository\Email\EmailPasswordReset\EmailPasswordResetRepositoryInterface;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmailService implements EmailServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserAuthServiceInterface
     */
    private $userAuthService;
    /**
     * @var EmailDomainRepository
     */
    private $emailDomainRepository;
    /**
     * @var EmailPasswordResetRepositoryInterface
     */
    private $emailPasswordResetRepository;

    /**
     * EmailService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserAuthServiceInterface $userAuthService
     * @param EmailDomainRepositoryInterface $emailDomainRepository
     * @param EmailPasswordResetRepositoryInterface $emailPasswordResetRepository
     */
    public function __construct(UserRepositoryInterface $userRepository,
                                UserAuthServiceInterface $userAuthService,
                                EmailDomainRepositoryInterface $emailDomainRepository,
                                EmailPasswordResetRepositoryInterface $emailPasswordResetRepository)
    {
        $this->emailPasswordResetRepository = $emailPasswordResetRepository;
        $this->emailDomainRepository = $emailDomainRepository;
        $this->userRepository = $userRepository;
        $this->userAuthService = $userAuthService;
    }

    public function sendResetPasswordEmail($params)
    {
        $result = [];
        if (isset($params['foreign_id'])) {
            $users[] = $this->userRepository->getByForeignIdAndCompany_id($params['foreign_id'], $params['company_id']);
        }elseif ($params['user'] == 'all') {
            $users = $this->userRepository->all();
        }elseif (is_array($params['user'])) {
            foreach ($params['user'] as $user) {
                if ($userModel = $this->userRepository->userById($user)) {
                    $users[] = $userModel;
                }else{
                    $result[$user] = 'this not found';
                }
            }
        }

        if (isset($users)) {
            foreach ($users as $user) {

                if (stristr($user->email, '@') && $this->emailDomainRepository->firstByEmail(stristr($user->email, '@', false))) {
                    $details['user_id'] = $user->id;
                    $details['email_content']['url'] = "https://mycent.kz/auth/" . $this->userAuthService->tokenResetPassword($user) . "/$user->username";
                    $details['email_content']['username'] = $user->username;
                    $details['email_content']['company_name'] = $user->company->full_name;
                    $details['email'] = $user->email;
                    dispatch(new SendEmailJob($details));
                }else {
                    $result[$user->username] = 'this email domain in not found';
                }
            }
            if ($result) {
                return $result;
            }else {
                return $users;
            }
        }else{
            return $result;
        }
    }

    /**
     * @param $file
     * @return string[]|void
     */
    public function saveFile($file)
    {
        foreach ($file as $f) {
            if (isset($f['url'])) {

                $url = urlencode($f['url']);
                $content = file_get_contents($url);
                $fileName = basename($f['url']);
                $this->saveEmailFile($content, $fileName);
                return ['file' => 'ok'];

            } elseif (isset($f['file'])) {

                $content = file_get_contents($f['file']->getRealPath());
                $fileName = $f['file']->getClientOriginalName();
                $this->saveEmailFile($content, $fileName);
                return ['file' => 'ok'];

            }
        }
    }

    public function saveEmailFile($content, $fileName)
    {
        return Storage::disk('local')->put("public/email/$fileName", $content);
    }
}
