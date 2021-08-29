<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetTokenGenerated extends Mailable implements ShouldQueue {
    
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
        $this->onQueue('reset_password_queue');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $url = env('APP_URL') . "/{$this->token}";
        return $this->view('mails.passwordResetTokenGeneratedMail')
                    ->subject('Resetar senha')
                    ->with(['url' => $url]);
    }
}
