<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);
    }

    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}
