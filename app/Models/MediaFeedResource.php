<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFeedResource extends Model
{
    public $incrementing = false;

    protected $hidden = [
        'filepath',
        'feed_id',
    ];
}
