<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssessmentSubmission extends Model
{
    use HasUuids;


    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function grader()
    {
        return $this->belongsTo(\App\Models\User::class, 'graded_by');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'submission_id');
    }
}
