<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\Inspections\Spam;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     */
    public function store(CreatePostRequest $request, $channelSlug, Thread $thread)
    {
        return $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ])->load('owner');
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

        $this->validate($request, ['body' => 'required|spamfree']);

        $reply->update(['body' => $request->body]);
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
        $this->validate($request, ['body' => 'required|spamfree']);
    }
}
