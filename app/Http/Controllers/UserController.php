<?php

namespace App\Http\Controllers;

use App\Enums\MediaCollectionType;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Get list of users
     */
    public function index(): AnonymousResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedIncludes('roles', 'avatar')
            ->paginate(request('perPage', 10));

        return UserResource::collection($users);
    }

    /**
     * Add a new user
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                ...$request->only('name', 'email'),
                'password' => bcrypt($password = Str::random(8)),
            ]);

            if ($request->has('roles') && $request->roles) {
                $user->assignRole($request->roles);
            }

            if ($request->has('avatar') && $request->avatar) {
                $user->addMediaFromRequest('avatar')
                    ->toMediaCollection(MediaCollectionType::Avatar->value);
            }

            $user->load('roles', 'avatar');

            // Mail::to($user)->queue(new UserCreated($user));

            return $user;
        });

        return new UserResource($user);
    }

    /**
     * Update user
     */
    public function update(StoreUserRequest $request, User $user): UserResource
    {
        $user = DB::transaction(function () use ($request, $user) {
            $user->fill($request->only('name', 'email'));
            $user->save();

            if ($request->has('roles') && $request->roles) {
                $user->syncRoles($request->roles);
            }

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
