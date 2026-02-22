<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
