<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Inspections\Spam;
use Illuminate\Http\Request;
use App\Filters\ThreadsFilter;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ThreadsFilter $filter, Channel $channel = null)
    {
        $threads = $this->getThreads($filter, $channel);

        if (request()->expectsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    public function getThreads(ThreadsFilter $filter, Channel $channel = null)
    {
        $query = Thread::query()->latest()->filter($filter);

        if ($channel && $channel->exists) {
            $query->where('channel_id', $channel->id);
        }

        return $query->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'title'      => 'required|spamfree',
                'body'       => 'required|spamfree',
                'channel_id' => 'required|exists:channels,id',
            ]
        );

        $thread = new Thread([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        $thread->creator()->associate(auth()->id());
        $thread->channel()->associate($request->channel_id);
        $thread->save();

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function show($channelSlug, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        return view('threads.show', compact('thread'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $channelSlug, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if ($request->expectsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }
}
