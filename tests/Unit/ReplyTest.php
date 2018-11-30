<?php

namespace Tests\Unit;

use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_reply_has_an_owner()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    public function test_it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = \Carbon\Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    public function test_it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply', [
            'body' => '@JaneDon wants to talk to @Rose'
        ]);

        $this->assertEquals(['JaneDon', 'Rose'], $reply->mentionedUsers());
    }

    public function test_it_warps_mentioned_usernames_in_the_body_within_archor_tags()
    {
        $reply = create('App\Reply', [
            'body' => 'Hi @Luff How are you?',
        ]);

        $this->assertEquals(
            'Hi <a href="/profiles/Luff">@Luff</a> How are you?',
            $reply->body
        );
    }
}
