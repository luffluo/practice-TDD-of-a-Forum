<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    public function activity()
    {
        return $this->hasMany('App\Activity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Illuminate\Database\Query\Builder
     */
    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
    }

    /**
     * @param $thread
     *
     * @return string
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf('users.%s.threads.%s', $this->id, $thread->id);
    }

    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            \Carbon\Carbon::now()
        );
    }

    public function getAvatarPathAttribute($avatar)
    {
        return $avatar ?: 'avatars/default.jpg';
    }

    public function confirm()
    {
        $this->confirmed = true;

        $this->confirmation_token = null;

        return $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['Luff']);
    }
}
