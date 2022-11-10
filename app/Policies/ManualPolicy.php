<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Manual;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;

class ManualPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user can delete manual
     */
    public function delete(Authenticatable $user, Manual $manual)
    {
        return $user->hasAnyRole(
            UserRole::Admin->value,
            UserRole::QATester->value
        );
    }
}
