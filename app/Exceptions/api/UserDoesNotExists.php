<?php

namespace App\Exceptions\api;

use Exception;
use Illuminate\Http\JsonResponse;

class UserDoesNotExists extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(Exception $exception) : JsonResponse
    {
        return response()->json(['error' => true, 'message' => $exception->getMessage()], 401);
    }
}
