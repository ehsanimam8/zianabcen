<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Note extends Model
{
    use HasUuids;


    protected $guarded = [];

    public function notable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
