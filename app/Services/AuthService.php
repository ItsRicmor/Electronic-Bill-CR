<?php


namespace App\Services;


use App\User;
use Illuminate\Http\Request;

interface AuthService
{
    public function authenticate(Request $request) : array ;
    public function signUp(Request $request) : array ;
    public function get(String $email);
    public function me(int $id);
    public function refresh(int $id);
}