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
        static::saving(function (CourseSession $session) {
            if ($session->instructor_user_id && $session->session_date && $session->session_start_time && $session->session_end_time) {
                // Check for overlapping sessions for this instructor
                $conflict = static::where('instructor_user_id', $session->instructor_user_id)
                    ->where('session_date', $session->session_date)
                    ->where('id', '!=', $session->id ?? 'dummy')
                    ->where(function ($query) use ($session) {
                        $query->whereBetween('session_start_time', [$session->session_start_time, $session->session_end_time])
                              ->orWhereBetween('session_end_time', [$session->session_start_time, $session->session_end_time])
                              ->orWhere(function ($q) use ($session) {
                                  $q->where('session_start_time', '<=', $session->session_start_time)
                                    ->where('session_end_time', '>=', $session->session_end_time);
                              });
                    })->exists();

                if ($conflict) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'instructor_user_id' => 'Instructor is already booked for another session during this time.',
                        'session_start_time' => 'Conflict with another scheduled session.',
                        'session_end_time' => 'Conflict with another scheduled session.'
                    ]);
                }
            }
        });

        static::created(function (CourseSession $session) {
            $session->generateAttendances();
        });
    }

    public function generateAttendances()
    {
        $enrolledStudents = \App\Models\SIS\CourseAccess::join('enrollments', 'course_access.enrollment_id', '=', 'enrollments.id')
            ->where('course_access.course_id', $this->course_id)
            ->where('course_access.is_active', true)
            ->distinct()
            ->pluck('enrollments.user_id');

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
