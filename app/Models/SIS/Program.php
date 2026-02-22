<?php

namespace App\Models\SIS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_fields' => 'json',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'program_courses')
            ->withPivot(['sequence', 'is_required'])
            ->withTimestamps();
    }
}
