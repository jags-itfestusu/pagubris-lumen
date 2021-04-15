<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    public $incrementing = false;

    public function creator()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected $fillable = [
        'content',
    ];

    public function answers()
    {
        return $this->hasMany(Feed::class, 'parent_feed_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Feed::class, 'parent_feed_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(MediaFeedResource::class);
    }
}
