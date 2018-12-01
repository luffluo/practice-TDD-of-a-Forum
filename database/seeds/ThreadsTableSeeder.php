<?php

use Illuminate\Database\Seeder;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Thread::query()->truncate();
        App\Reply::query()->truncate();

        $users = App\User::all();
        $channels = App\Channel::all();

        $users->each(function ($user) use ($users, $channels) {

            $thread = factory('App\Thread')->create([
                'user_id'    => $user->id,
                'channel_id' => $channels->random()->id,
            ]);

            factory('App\Reply', mt_rand(0, 40))->create([
                'user_id'   => $users->random()->id,
                'thread_id' => $thread->id,
            ]);
        });
    }
}
