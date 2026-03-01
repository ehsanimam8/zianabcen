<?php

namespace App\Models\SIS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'enrolled_at' => 'date',
        'expires_at' => 'date',
        'completed_at' => 'date',
    ];
    protected static function booted()
    {
        static::created(function (Enrollment $enrollment) {
            if ($enrollment->course) {
                CourseAccess::updateOrCreate(
                    [
                        'enrollment_id' => $enrollment->id,
                        'course_id' => $enrollment->course->id,
                    ],
                    [
                        'is_active' => true,
                        'access_starts_at' => now(),
                    ]
                );
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function courseAccess()
    {
        return $this->hasMany(CourseAccess::class);
    }
}
