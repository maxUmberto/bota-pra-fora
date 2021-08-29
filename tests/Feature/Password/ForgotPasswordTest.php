<?php

namespace Tests\Feature\Password;

// Models;
use App\Models\PasswordReset;
use App\Models\User;

// Mails
use App\Mail\PasswordResetTokenGenerated;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Test if an user can generate a token to reset its password and if
     * an email with its token is sent
     *
     * @return void
     */
    public function testGenerateResetPasswordToken() {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message'
                ]);

        Mail::assertQueued(PasswordResetTokenGenerated::class);

        $this->assertDatabaseCount('password_resets', 1);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);
    }

    /**
     * Test if the user forgots his password and he already had a token it will
     * delete the old one ang generate a new
     * 
     * @return void
     */
    public function testGenerateResetPasswordTokenWithTokenAlreadyExistent() {
        Mail::fake();
        $user = User::factory()->create();
        $token = Hash::make($user->email . date("Y-m-d H:i:s"));

        $old_reset_token = PasswordReset::create([
            'email' => $user->email,
            'token' => $token
        ]);

        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message'
                ]);

        $this->assertDatabaseCount('password_resets', 1);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email
        ]);

        $new_reset_token = PasswordReset::where('email', $user->email)->first();
        $this->assertTrue($new_reset_token->token !== $old_reset_token->token);
    }

    /**
     * Test if the user CANT reset its password without passing its email
     * 
     * @return void
     */
    public function testForgotPasswordWithoutPassingAnEmail() {
        $response = $this->postJson('/api/forgot-password', ['email' => null]);

        $response->assertStatus(422);
    }

    /**
     * Test the user cant generate a reset password token for an email
     * that is not registered at the system
     * 
     * @return void
     */
    public function testForgotPasswordWithAnUnexistentEmail() {
        $response = $this->postJson('/api/forgot-password', ['email' => 'teste@teste.teste']);

        $response->assertStatus(422);
    }
}
