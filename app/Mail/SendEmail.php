<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email_content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_content)
    {
        $this->email_content = $email_content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = $this->email_content;
        $email = $this->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
            ->subject('Уведомление с портала mycent.kz')
            ->replyTo(env('MAIL_REPLY_TO'))
            ->view('email.email', compact('content'));
//            ->attach(public_path() . '/storage/email/Инструкция регистрации-авторизации mycent kz.pdf');

        return $email;

    }
}
