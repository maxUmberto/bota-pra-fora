<?php

namespace Tests\Feature\Password;

// Models;
use App\Models\PasswordReset;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase {

    use DatabaseMigrations;

    public $password = '123456';

    /**
     * Check if the user can reset the password, if the new password
     * is correctly saved and if the token is deleted from table
     *
     * @return void
     */
    public function testResetPassword(){
        $user = User::factory()->create();
        $token = Hash::make($user->email . date("Y-m-d H:i:s"));

        $password_reset = PasswordReset::create([
            'email' => $user->email,
            'token' => $token
        ]);

        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'password'              => $this->password,
            'email'                 => $user->email,
            'token'                 => $token
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message'
                ]);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $user->email,
            'token' => $token
        ]);
        $this->assertDeleted($password_reset);

        $user = User::where('email', $user->email)->first();
        $this->assertTrue(Hash::check($this->password, $user->password));
    }

    /**
     * Test the user cant reset its password without passing the token
     * 
     * @return void
     */
    public function testUserCantResetPasswordWithoutToken() {
        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'password'              => $this->password,
            'email'                 => $this->faker->unique()->safeEmail(),
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test the user cant reset its password without passing the email
     * 
     * @return void
     */
    public function testUserCantResetPasswordWithoutEmail() {
        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'password'              => $this->password,
            'token'                 => $this->faker->sha256()
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test the user cant reset its password without passing the password
     * 
     * @return void
     */
    public function testUserCantResetPasswordWithoutPassword() {
        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'email'                 => $this->faker->unique()->safeEmail(),
            'token'                 => $this->faker->sha256()
        ]);

        $response->assertStatus(422);
    }
    
    /**
     * Test the user cant reset its password without passing the password confirmation
     * 
     * @return void
     */
    public function testUserCantResetPasswordWithoutPasswordConfirmation() {
        $response = $this->postJson('/api/reset-password', [
            'password' => $this->password,
            'email'    => $this->faker->unique()->safeEmail(),
            'token'    => $this->faker->sha256()
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test the user cant reset its password without the password confirmation match
     * 
     * @return void
     */
    public function testUserCantResetPasswordWithoutPasswordConfirmationMatch() {
        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'password'              => $this->password."123",
            'email'                 => $this->faker->unique()->safeEmail(),
            'token'                 => $this->faker->sha256()
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test the user cant reset its password without the email matching the token
     * at the password_resets table
     * 
     * @return void
     */
    public function testUserEmailMustMatchTheToken() {
        $user = User::factory()->create();
        $token = Hash::make($user->email . date("Y-m-d H:i:s"));

        $password_reset = PasswordReset::create([
            'email' => $user->email,
            'token' => $token
        ]);

        $response = $this->postJson('/api/reset-password', [
            'password_confirmation' => $this->password,
            'password'              => $this->password,
            'email'                 => $user->email,
            'token'                 => $this->faker->sha256()
        ]);

        $response->assertStatus(404);
    }
}
