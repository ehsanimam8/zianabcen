<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'amount'     => 'decimal:2',
        'donated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function sponsoredStudent()
    {
        return $this->belongsTo(\App\Models\User::class, 'sponsored_student_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeThisMonth($query)
    {
        return $query
            ->whereMonth('donated_at', now()->month)
            ->whereYear('donated_at', now()->year);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('donation_type', $type);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format((float) $this->amount, 2);
    }
}
