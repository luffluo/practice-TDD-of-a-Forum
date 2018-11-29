<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{
    // use DatabaseMigrations;

    public function test_mentioned_users_in_a_reply_are_notified()
    {
        $this->signIn($john = create('App\User', ['name' => 'John']));
        $jane = create('App\User', ['name' => 'Jane']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Hi @Jane there.',
        ]);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->fresh()->notifications);
    }
}
