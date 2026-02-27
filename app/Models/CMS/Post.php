<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_publish_at' => 'datetime',
        'is_sticky' => 'boolean',
        'custom_fields' => 'json',
    ];

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_user_id');
    }

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
