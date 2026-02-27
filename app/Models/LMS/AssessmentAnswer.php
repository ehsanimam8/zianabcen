<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssessmentAnswer extends Model
{
    use HasUuids;


    protected $guarded = [];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(AssessmentSubmission::class, 'submission_id');
    }

    public function question()
    {
        return $this->belongsTo(AssessmentQuestion::class, 'question_id');
    }
}
