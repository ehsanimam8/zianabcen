<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'marked_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_user_id');
    }

    public function marker()
    {
        return $this->belongsTo(\App\Models\User::class, 'marked_by_user_id');
    }
}
