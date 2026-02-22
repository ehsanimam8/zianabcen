<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'registered_at' => 'datetime',
        'custom_fields' => 'json',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\CRM\Contact::class);
    }
}
