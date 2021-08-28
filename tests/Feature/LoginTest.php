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

  public function testCreateUserWithoutFirstName(){
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

  public function testCreateUserWithoutLastName() {
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
  
  public function testCreateUserWithoutEmail() {
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
  
  public function testCreateUserWithoutPassword() {
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

  public function testCreateUserWithAlreadyRegisteredMail() {
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
  
  public function testCreateUser() {

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

  public function testLoginUserWithoutSignUp() {

    $user = User::factory()->make();

    $response = $this->postJson('/api/login', [
      'email'    => $user->email,
      'password' => $user->password
    ]);

    $response->assertStatus(404);
  }

  public function testLoginUserWithWrongPassword() {

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

  public function testLogoutUserWithoutBearerToken() {

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
