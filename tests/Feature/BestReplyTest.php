<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BestReplyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
