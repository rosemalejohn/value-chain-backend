<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentials;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }

    /**
     * Authenticate user using username and password
     */
    public function login(UserLoginRequest $request)
    {
        /** @var User */
        $user = User::whereEmail($request->email)
            ->with('avatar')
            ->first();

        throw_if(is_null($user), InvalidCredentials::class);

        /** @var NewAccessToken */
        $newAccessToken = $user->createToken($request->header('user-agent'));

        return $this->respondWithToken(
            $newAccessToken->plainTextToken,
            $this->user($user)
        );
    }

    /**
     * Get the authenticated User.
     */
    public function user(User $user = null): UserResource
    {
        if (is_null($user)) {
            /** @var \App\Models\User */
            $user = auth()->user();
        }

        $user->load('avatar');

        return new UserResource($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
