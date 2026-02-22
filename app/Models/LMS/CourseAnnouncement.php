<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class CourseAnnouncement extends Model
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(\App\Models\User::class, 'instructor_user_id');
    }
}
