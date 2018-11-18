<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

    protected $table = 'replies';

    protected $fillable = ['user_id', 'thread_id', 'body'];

    protected $with = ['owner', 'favorites'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
