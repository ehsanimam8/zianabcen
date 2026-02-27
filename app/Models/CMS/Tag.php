<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tag extends Model
{
    use HasUuids;


    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
