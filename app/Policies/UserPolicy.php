<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(User $signInUser, User $user)
    {
        return $signInUser->id == $user->id;
    }
}
