<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class SignUpValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required|min:8'
        ));
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->getMessageBag()), 400);
        }
        return $next($request);
    }
}
