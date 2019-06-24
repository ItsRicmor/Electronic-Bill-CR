<?php


namespace App\Dao;


use App\User;
use Illuminate\Http\Request;

interface AuthDao
{
    public function authenticate($credentials) : User;
    public function signUp(Request $request) : User;
    public function get(String $email);
    public function me(int $id);
}