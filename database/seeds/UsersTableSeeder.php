<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::query()->truncate();

        factory('App\User')->create([
            'email' => 'luffluo@outlook.com',
            'name'  => 'Luff',
        ]);
    }
}
