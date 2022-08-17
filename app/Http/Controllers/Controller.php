<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user, $statusCode = 200)
    {
        return response()->json(['data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => null,
            'user' => $user,
        ]], $statusCode)->header('Authorization', $token);
    }

    /**
     * return a empty data response.
     *
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithEmptyData($statusCode = 200)
    {
        return response()->json([], $statusCode);
    }
}
