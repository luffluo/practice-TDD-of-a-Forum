<?php

namespace App\Filters;

use App\User;

class ThreadsFilter extends Filter
{
    protected $filters = ['by'];

    public function by($username)
    {
        $user = User::query()->where('name', $username)->first();

        return $this->builder->where('user_id', $user->id ?? 0);
    }
}
