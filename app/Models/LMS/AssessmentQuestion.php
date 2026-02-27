<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssessmentQuestion extends Model
{
    use HasUuids;


    protected $guarded = [];

    protected $casts = [
        'options' => 'json',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'question_id');
    }
}
