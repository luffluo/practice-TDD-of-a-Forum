<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $table = 'threads';

    protected $fillable = ['title', 'body'];

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->id}";
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
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
        return $this->replies()->create($array);
    }
}
