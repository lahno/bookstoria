<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderUserRegister extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $pass;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $pass)
    {
        $this->email = $user->email;
        $this->pass = $pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('SITE_EMAIL'), env('APP_NAME'))
            ->view('email.mail_register_new_user')
            ->subject('Register new user')
            ->with([
                'email' => $this->email,
                'pass' => $this->pass
            ]);
    }
}
