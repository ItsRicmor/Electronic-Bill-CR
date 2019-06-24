<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'signUp']]);
        $this->middleware('signUpValidator', ['only' => ['signUp']]);
        $this->authService = $authService;
    }

    /*
     * @throws InvalidCredentials
     * @throws JWTException
     */
    public function authenticate(Request $request)
    {
        list($user, $token) = $this->authService->authenticate($request);
        return $this->respondWithToken($token, $user);
    }

    public function signUp(Request $request)
    {
        list($user, $token) = $this->authService->signUp($request);
        return $this->respondWithToken($token, $user, 201);
    }

    public function me()
    {
        $user = $this->authService->me(auth()->user()->id);
        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        list($user, $token) = $this->authService->refresh(auth()->user()->id);
        return $this->respondWithToken($token, $user);
    }

    protected function respondWithToken($token, User $user, $statusCode = 200)
    {
        return response()->json(array(
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ), $statusCode);
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function createUserToken($user)
    {
        return JWTAuth::fromUser($user);
    }
}
