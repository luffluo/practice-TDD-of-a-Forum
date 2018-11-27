<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $channelSlug, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $channelSlug, Thread $thread)
    {
        $this->validateReply($request);

        $reply = $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ]);

        if ($request->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reply               $reply
     *
     * @return \Illuminate\Http\Response|array
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validateReply($request);

        $reply->update([
            'body'    => $request->body,
        ]);

        if ($request->expectsJson()) {
            return ['status' => 'Reply updated'];
        }

        return back()->with('flash', 'Your reply has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply $reply
     *
     * @return \Illuminate\Http\Response|array
     */
    public function destroy(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if ($request->expectsJson()) {
            return ['status' => 'Reply deleted'];
        }

        return back();
    }

    public function validateReply(Request $request)
    {
        $this->validate($request, ['body' => 'required']);

        app(Spam::class)->detect($request->body);
    }
}
