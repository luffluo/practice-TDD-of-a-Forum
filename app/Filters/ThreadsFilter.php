<?php

namespace App\Filters;

use App\User;

class ThreadsFilter extends Filter
{
    protected $filters = ['by', 'popularity'];

    public function by($username)
    {
        $user = User::query()->where('name', $username)->first();

        return $this->builder->where('user_id', $user->id ?? 0);
    }

    public function popularity()
    {
        // 清空 以前的 orders 条件
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }
}
