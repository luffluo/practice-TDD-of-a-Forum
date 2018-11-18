<?php

namespace App;

trait Favoritable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    /**
     * 点赞
     * @return Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * @return bool
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
