<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserTest extends TestCase
{
    public function test_can_create_post(){
        $data = array(
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '12345'
        );
        $jsonStructure = array(
            'access_token',
            'token_type',
            'user',
            'expires_in'
        );
        $this->post(route('users.signUp'), $data)
            ->assertStatus(200)
            ->assertJsonStructure($jsonStructure);
    }
}
