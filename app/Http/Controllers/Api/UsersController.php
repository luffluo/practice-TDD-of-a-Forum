<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('name');

        return User::query()
            ->where('name', 'like', "%{$search}%")
            ->take(5)
            ->pluck('name');
    }
}
