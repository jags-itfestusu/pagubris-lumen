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
        'content'
    ];
}
