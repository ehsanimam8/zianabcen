<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasUuids;

    protected $guarded = [];

    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED   = 'completed';

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForUser($query, string $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status'       => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function markInProgress(): void
    {
        $this->updateOrFail([
            'status'     => self::STATUS_IN_PROGRESS,
            'started_at' => $this->started_at ?? now(),
        ]);
    }
}
