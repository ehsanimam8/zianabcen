<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'is_required' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
