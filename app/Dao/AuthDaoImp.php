<?php


namespace App\Dao;


use App\Contributor;
use App\Emitter;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthDaoImp implements AuthDao
{
    /**
     * @param $credentials
     * @return User
     */
    public function authenticate($credentials): User
    {
        return User::with('emitter.contributor')->where('email', $credentials['email'])->first();
    }

    /**
     * @param Request $request
     * @return User
     */
    public function signUp(Request $request): User
    {
        DB::beginTransaction();
        $user = new User([
            'password' => Hash::make($request->password),
            'email' => $request->email
        ]);
        $user->save();
        $emitter = new Emitter($request->emitter);
        $user->emitter()->save($emitter);
        $contributor = new Contributor($request->contributor);
        $contributor->save();
        $contributor->emitter()->save($emitter);
        DB::commit();
        $user = User::with('emitter.contributor')->find($user->id);
        return $user;
    }

    /**
     * @param int $id
     * @return User
     * @return Boolean
     */
    public function me(int $id)
    {
        return $user = User::with('emitter.contributor')->find($id) ?? false;
    }

    /**
     * @param String $email
     * @return User
     * @return Boolean
     */
    public function get(String $email)
    {
        return User::where('email', $email)->first() ?? false;
    }
}