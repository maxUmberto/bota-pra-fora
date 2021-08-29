<?php

namespace Tests\Unit\Mails;

// Mails
use App\Mail\ForgotPasswordMail;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgotPasswordMailTest extends TestCase {

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testForgotPasswordMailContent(){
        $token = Hash::make($this->faker->unique()->safeEmail() . date("Y-m-d H:i:s"));
        
        $mailable = new ForgotPasswordMail($token);

        $mailable->assertSeeInHtml($token);
        $mailable->assertSeeInHtml('Resetar senha');
        $this->assertEquals('Resetar sua senha', $mailable->build()->subject);
    }
}
