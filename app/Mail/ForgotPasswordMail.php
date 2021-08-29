<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable implements ShouldQueue {
    
    use Queueable, SerializesModels;

    public $token;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * 
     * @return void
     */
    public function __construct(string $token) {
        $this->token = $token;
        $this->onQueue('mail');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $url = env('APP_URL') . "/{$this->token}";
        return $this->view('mails.forgotPasswordMail')
                    ->subject('Resetar sua senha')
                    ->with(['url' => $url]);
    }
}
