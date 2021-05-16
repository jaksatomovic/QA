<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the authenticated user has permission to edit profile.
     *
     * @param  User  $user
     * @return bool
     */
    public function access(User $user)
    {
        return auth()->id() == $user->id ? true : null;
    }
}
