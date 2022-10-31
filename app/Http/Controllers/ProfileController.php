<?php

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Update profile
     */
    public function update(ProfileRequest $request): UserResource
    {
        $user = $request->user();

        $user = DB::transaction(function () use ($request, $user) {
            $user->fill($request->only('name', 'email'));

            if ($request->has('password') && $request->password) {
                $user->password = bcrypt('password');
            }

            $user->save();

            if ($request->has('avatar') && $request->avatar) {
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection(MediaCollectionType::Avatar->value);
            }

            $user->load('roles', 'avatar');

            return $user;
        });

        return new UserResource($user);
    }
}
