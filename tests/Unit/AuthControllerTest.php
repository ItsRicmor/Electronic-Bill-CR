<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private static $jsonStructure = array(
        'access_token',
        'token_type',
        'expires_in'
    );

    private static $errorStructure = array('error', 'message');

    public function test_can_create_user()
    {
        $email = $this->faker->email;
        $password = '12345678';
        $this->createUser($email, $password)
            ->assertStatus(201)
            ->assertJsonStructure(self::$jsonStructure);
    }

    public function test_invalid_content_fields_create_user()
    {
        $email = 'failEmail.com';
        $password = '12345';
        $this->createUser($email, $password)
            ->assertStatus(400)
            ->assertJsonStructure(self::$errorStructure);
    }

    public function test_invalid_key_fields_create_user()
    {
        $data = array(
            'emailWrongField' => $this->faker->email,
            'passwordWrongField' => '12345678'
        );
        $response = $this->post(route('users.signUp'), $data);
        $response->assertStatus(400)
            ->assertJsonStructure(self::$errorStructure);
    }

    public function test_can_authenticate()
    {
        $email = $this->faker->email;
        $password = '12345678';

        $this->createUser($email, $password);
        $this->refreshApplication();

        $credentials = array('email' => $email, 'password' => $password);

        $response = $this->loginUser($credentials);
        $response->assertStatus(200)
            ->assertJsonStructure(self::$jsonStructure);
    }

    public function test_invalid_credentials()
    {
        $invalidCredentials = array('email' => 'invalid_email@gmail.com', 'password' => 'invalid_password');
        $this->loginUser($invalidCredentials)
            ->assertStatus(401)
            ->assertJsonStructure(self::$errorStructure);
    }

    public function test_can_me()
    {
        $response = $this->apiAs('GET', route('users.me'));
        $response->assertStatus(200)
            ->assertJsonStructure(array('email', 'emitter'));
    }

    protected function apiAs($method, $uri, array $data = [], array $headers = [], $user = null)
    {
        $user = $user ? $user : factory(User::class)->create();

        $headers = array_merge(
            ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)],
            $headers
        );
        return $this->json($method, $uri, $data, $headers);
    }

    protected function createUser(string $email, string $password)
    {
        $data = array(
            'email' => $email,
            'password' => $password,
            'contributor' => [
                'name' => $this->faker->name,
                'email' => $email,
                'id_type' => '02',
                'id_number' => '901110534',
                'code_country' => '506',
                'phone_number' => '85807271',
                'fax_number' => '85807271'
            ],
            'emitter' => [
                'certificate_file_name' => 'prueba.p12',
                'certificate_password' => '12345'
            ]
        );
        return $this->post(route('users.signUp'), $data);
    }

    protected function loginUser($credentials)
    {
        return $this->post(route('users.authenticate'), $credentials);
    }

}
