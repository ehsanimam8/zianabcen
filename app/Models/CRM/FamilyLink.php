<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FamilyLink extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(\App\Models\User::class, 'parent_user_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_user_id');
    }
}
