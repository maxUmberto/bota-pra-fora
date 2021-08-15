<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{

  use DatabaseMigrations;

  const SIGN_UP_DATA = [
    "first_name" => "Max",
    "last_name"  => "Santos",
    "email"      => "teste@gmail.com",
    "password"   => 123456
  ];

  public function test_create_user_without_first_name(){
    $data = self::SIGN_UP_DATA;
    unset($data['first_name']);

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(422)
            ->assertJsonStructure([
              'message',
              'errors' => [
                'first_name'
              ]
            ]);
  }

  public function test_create_user_without_last_name() {
    $data = self::SIGN_UP_DATA;
    unset($data['last_name']);

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(422)
            ->assertJsonStructure([
              'message',
              'errors' => [
                'last_name'
              ]
            ]);
  }
  
  public function test_create_user_without_email() {
    $data = self::SIGN_UP_DATA;
    unset($data['email']);

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(422)
            ->assertJsonStructure([
              'message',
              'errors' => [
                'email'
              ]
            ]);
  }
  
  public function test_create_user_without_password() {
    $data = self::SIGN_UP_DATA;
    unset($data['password']);

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(422)
            ->assertJsonStructure([
              'message',
              'errors' => [
                'password'
              ]
            ]);
  }

  public function test_create_user_with_already_registered_mail() {
    User::factory()->create([
      'email' => self::SIGN_UP_DATA['email']
    ]);

    $data = self::SIGN_UP_DATA;

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(422)
            ->assertJsonStructure([
              'message',
              'errors' => [
                'email'
              ]
            ])
            ->assertJsonFragment([
              'email' => [
                'Esse email já está cadastrado'
              ]
            ]);
  }
  
  public function test_create_user() {

    $data = self::SIGN_UP_DATA;

    $response = $this->postJson('/api/sign-up', $data);

    $response->assertStatus(200)
            ->assertJsonStructure([
              'token_type',
              'token',
              'message',
              'success'
            ])
            ->assertJsonFragment(['token_type' => 'bearer'])
            ->assertJsonFragment(['message' => 'Usuário cadastrado com sucesso'])
            ->assertJsonFragment(['success' => true]);

    $this->assertDatabaseHas('users', [
      'first_name' => self::SIGN_UP_DATA['first_name'],
      'last_name'  => self::SIGN_UP_DATA['last_name'],
      'email'      => self::SIGN_UP_DATA['email'],
    ]);
  }

  public function test_login_user_without_sign_up() {

    $user = User::factory()->make();

    $response = $this->postJson('/api/login', [
      'email'    => $user->email,
      'password' => $user->password
    ]);

    $response->assertStatus(404);
  }

  public function test_login_user_with_wrong_password() {

    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
      'email'    => $user->email,
      'password' => 'password2'
    ]);

    $response->assertStatus(401)
            ->assertJsonStructure([
              'message',
              'success'
            ])
            ->assertJsonFragment(['message' => 'Email ou senha incorretos'])
            ->assertJsonFragment(['success' => false]);
  }

  public function test_logout_user_without_bearer_token() {

    $user = User::factory()->create();

    $response = $this->post('/api/logout');

    $response->assertStatus(412)
            ->assertJsonStructure([
              'message',
              'success'
            ])
            ->assertJsonFragment(['message' => 'É necessário informar um token'])
            ->assertJsonFragment(['success' => false]);
  }
  
  public function test_logout_user() {

    $user = User::factory()->create();

    $response = $this->actingAs($user)
                     ->post('/api/logout');

    $response->assertStatus(204);
  }
}
