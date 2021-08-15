<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string|null                                $driver
     * @return $this
     */
    public function actingAs($user, $driver = null)
    {
        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', "Bearer {$token}");
        parent::actingAs($user);
        
        return $this;
    }

    protected function setUpFaker(){
        $this->faker = $this->makeFaker('pt_BR');
    }
}
