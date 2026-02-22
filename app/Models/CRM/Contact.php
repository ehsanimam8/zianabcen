<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'custom_fields' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
