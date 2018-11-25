<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(
    )
    {
        $thread = create('App\Thread');

        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body'    => 'Some another reply here',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    public function test_a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json();

        $this->assertCount(1, $response);
    }

    public function test_a_user_can_clear_a_notification()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->fresh()->unreadNotifications);

            $this->delete('/profiles/' . auth()->user()->name . "/notifications/" . $user->unreadNotifications->first()->id);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });

    }
}
