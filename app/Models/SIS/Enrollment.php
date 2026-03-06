<?php

namespace App\Models\SIS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'enrolled_at'  => 'date',
        'expires_at'   => 'date',
        'completed_at' => 'date',
    ];

    // ─── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Active enrollments — student has full LMS access.
     * Single source of truth for enrollment status checks.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['Enrolled', 'Active']);
    }

    // ─── Relationships ──────────────────────────────────────────────────────────

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
}
