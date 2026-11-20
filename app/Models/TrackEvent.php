<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackEvent extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];
}
