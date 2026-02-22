<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'event_start' => 'datetime',
        'event_end' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
