<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'pattern_config' => 'json',
        'schedule_start_date' => 'date',
        'schedule_end_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\SIS\Course::class);
    }

    public function sessions()
    {
        return $this->hasMany(CourseSession::class);
    }

    protected static function booted()
    {
        static::created(function (CourseSchedule $schedule) {
            $schedule->generateSessions();
        });
    }

    public function generateSessions()
    {
        if ($this->pattern_type !== 'weekly_recurring' || empty($this->pattern_config)) {
            return;
        }

        $startDate = \Carbon\Carbon::parse($this->schedule_start_date);
        $endDate = \Carbon\Carbon::parse($this->schedule_end_date);
        $daysOfWeek = $this->pattern_config['days_of_week'] ?? [];
        $startTime = $this->pattern_config['start_time'] ?? null;
        $endTime = $this->pattern_config['end_time'] ?? null;
        $instructorId = $this->pattern_config['instructor_user_id'] ?? null;

        if (empty($daysOfWeek) || !$startTime) {
            return;
        }

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Carbon dayOfWeek returns 0 (Sunday) to 6 (Saturday)
            if (in_array((string)$currentDate->dayOfWeek, $daysOfWeek)) {
                $this->sessions()->create([
                    'course_id' => $this->course_id,
                    'instructor_user_id' => $instructorId,
                    'session_date' => $currentDate->format('Y-m-d'),
                    'session_start_time' => $startTime,
                    'session_end_time' => $endTime,
                    'timezone' => $this->timezone,
                ]);
            }
            $currentDate->addDay();
        }
    }
}
