<?php

namespace App\Models\SIS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_fields' => 'json',
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_courses')
            ->withPivot(['sequence', 'is_required'])
            ->withTimestamps();
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function modules()
    {
        return $this->hasMany(\App\Models\LMS\Module::class);
    }
}
