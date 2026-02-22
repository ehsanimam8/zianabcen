<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CourseSession extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'session_date' => 'date',
        'is_cancelled' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }

    protected static function booted()
    {
        static::created(function (CourseSession $session) {
            $session->generateAttendances();
        });
    }

    public function generateAttendances()
    {
        $enrolledStudents = \App\Models\SIS\CourseAccess::where('course_id', $this->course_id)
            ->where('is_active', true)
            ->distinct()
            ->pluck('user_id');

        foreach ($enrolledStudents as $studentId) {
            $this->attendances()->firstOrCreate([
                'student_user_id' => $studentId,
            ], [
                'status' => 'pending', // Use a default pending/absent status
            ]);
        }
    }

    public function schedule()
    {
        return $this->belongsTo(CourseSchedule::class, 'course_schedule_id');
    }

    public function instructor()
    {
        return $this->belongsTo(\App\Models\User::class, 'instructor_user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
