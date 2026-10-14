<?php

namespace App\Console\Commands;

use App\Http\Services\Email\EmailServiceInterface;
use App\Repository\User\UserRepositoryInterface;
use Illuminate\Console\Command;

class CheckAndSendEmailPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkUserTokenAndSend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var EmailServiceInterface
     */
    private $emailService;

    /**
     * Create a new command instance.
     *
     * @param UserRepositoryInterface $userRepository
     * @param EmailServiceInterface $emailService
     */
    public function __construct(UserRepositoryInterface $userRepository, EmailServiceInterface $emailService)
    {
        $this->emailService = $emailService;
        $this->userRepository = $userRepository;
        parent::__construct();

    }

    /**
     * @return mixed
     */
    public function handle()
    {
            foreach ($this->userRepository->getUsersNoToken() as $model) {
                $user['user'][] = $model->id;
            }
            print_r( $this->emailService->sendResetPasswordEmail($user));
    }
}
