<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $table = 'threads';

    protected $fillable = ['title', 'body', 'slug', 'best_reply_id', 'locked'];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Thread $thread) {
            $thread->update([
                'slug' => $thread->title,
            ]);
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * 作者
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * 添加评论
     *
     * @param array $array
     *
     * @return Model
     */
    public function addReply(array $array)
    {
        $reply = $this->replies()->create($array);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    /**
     * @param $user
     *
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply($reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
