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
}
