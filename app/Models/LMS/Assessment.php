<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }
}
