<?php

namespace App\Models\SIS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProgramCourse extends Pivot
{
    use HasUuids;

    protected $table = 'program_courses';
    public $incrementing = false;
    protected $keyType = 'string';
}
