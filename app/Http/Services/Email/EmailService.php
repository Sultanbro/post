<?php


namespace App\Http\Services\Email;


use App\Http\Services\Authenticate\UserAuthServiceInterface;
use App\Jobs\SendEmailJob;
use App\Repository\User\UserRepositoryInterface;
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
     * EmailService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserAuthServiceInterface $userAuthService
     */
    public function __construct(UserRepositoryInterface $userRepository, UserAuthServiceInterface $userAuthService)
    {
        $this->userRepository = $userRepository;
        $this->userAuthService = $userAuthService;
    }

    public function sendResetPasswordEmail($params)
    {
        $result = [];
        if ($params['user'] == 'all') {
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

                $details['email_content']['content'] = "Для обновления пароли перейдите по ссылке: http://mycent.kz/auth/" . $this->userAuthService->tokenResetPassword($user) . "/$user->email";
                $details['email'] = $user->email;
                dispatch(new SendEmailJob($details));
            }
            return 'ok';
        }else{
            return $result;
        }
    }

    /**
     * @inheritDoc
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
