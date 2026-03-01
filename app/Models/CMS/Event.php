<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'event_start' => 'datetime',
        'event_end' => 'datetime',
    ];

    protected $appends = ['status'];

    public function getStatusAttribute()
    {
        $now = now();
        if ($now->isBefore($this->event_start)) {
            return 'Upcoming';
        } elseif ($now->isAfter($this->event_end ?? $this->event_start->copy()->addHours(2))) {
            return 'Past';
        }
        return 'Ongoing';
    }

    public function hasCapacity(): bool
    {
        if (is_null($this->capacity)) {
            return true; // No limit
        }
        return $this->registrations()->count() < $this->capacity;
    }



    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
