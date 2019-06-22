<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'signUp']]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(array('error' => 'invalid_credentials', 'messages' => array()), 401);
            }
        } catch (JWTException $e) {
            return response()->json(array('error' => 'could_not_create_token', 'messages' => $e->getMessage()), 500);
        }
        $user = User::where('email', $request['email'])->first();
        // all good so return the token
        $token = $this->createUserToken($user);
        return $this->respondWithToken($token, $user);
    }

    public function signUp(Request $request)
    {

        $validator = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required|min:8',
            'name' => 'required|string'
        ));
        if ($validator->fails()) {
            return response()->json(array('error' => 'invalid_fields', 'messages' => $validator->getMessageBag()), 400);
        }
        $user = new User(array(
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'email' => $request->email
        ));
        $user->save();
        $token = $this->createUserToken($user);
        return $this->respondWithToken($token, $user, 201);
    }

    public function me()
    {
        $user = User::find(auth()->user()->id);
        $user = $user->only(array('name', 'email'));
        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $user = User::find(auth()->user()->id);
        $token = $this->createUserToken($user);
        return $this->respondWithToken($token, $user);
    }

    protected function respondWithToken($token, User $user, $statusCode = 200)
    {
        $user = $user->only(array('name', 'email'));
        return response()->json(array(
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
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
