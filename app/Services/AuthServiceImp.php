<?php


namespace App\Services;


use App\Dao\AuthDao;
use App\Exceptions\api\InvalidCredentials;
use App\Exceptions\api\UserAlreadyExists;
use App\Exceptions\api\UserDoesNotExists;
use App\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceImp implements AuthService
{

    protected $authDao;

    public function __construct(AuthDao $authDao)
    {
        $this->authDao = $authDao;
    }
    /**
     * @throws InvalidCredentials
     * @throws JWTException
     * @param Request $request
     * @return array
     * @throws InvalidCredentials
     */
    public function authenticate(Request $request): array
    {
        $credentials = $request->only('email', 'password');
        if (!$validate = JWTAuth::attempt($credentials)) {
            throw new InvalidCredentials('Correo o contraseña incorrectos');
        }
        $user = $this->authDao->authenticate($credentials);
        $token = User::createToken($user);
        return array($user, $token);
    }

    public function signUp(Request $request) : array
    {
        if($this->get($request->email)){
            throw new UserAlreadyExists('El email ya esta en uso');
        }
        $user = $this->authDao->signUp($request);
        $token = User::createToken($user);
        return array($user, $token);
    }

    /**
     * @param int $id
     * @return User
     * @throws UserDoesNotExists
     */
    public function me(int $id) : User
    {
        return $this->getUserById($id);
    }

    /**
     * @param int $id
     * @return array
     * @throws UserDoesNotExists
     */
    public function refresh(int $id)
    {
        $user = $this->getUserById($id);
        $token = User::createToken($user);
        return array($user, $token);
    }

    /**
     * @param String $email
     * @return User
     * @return Boolean
     */
    public function get(String $email)
    {
        return $this->authDao->get($email);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws UserDoesNotExists
     */
    protected function getUserById(int $id)
    {
        $user = $this->authDao->me($id);
        if (!$user) {
            throw new UserDoesNotExists('EL usuario no existe');
        }
        return $user;
    }
}