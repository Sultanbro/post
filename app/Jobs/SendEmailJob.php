<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use App\Repository\Email\EmailPasswordReset\EmailPasswordResetRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->details['email'])->send(new SendEmail($this->details['email_content']));
        if ($emailReset = app(EmailPasswordResetRepositoryInterface::class)->firstByUserId($this->details['user_id'])) {
            app(EmailPasswordResetRepositoryInterface::class)->update($emailReset->id, ['status' => 1]);
        } else {
            app(EmailPasswordResetRepositoryInterface::class)->create(['user_id' => $this->details['user_id'], 'status' => 1]);
        }
    }
}
