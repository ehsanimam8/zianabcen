<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(\App\Models\SIS\Enrollment::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }

    public function recorder()
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by_user_id');
    }
}
