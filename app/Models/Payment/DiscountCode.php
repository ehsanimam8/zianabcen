<?php

namespace App\Models\Payment;

use App\Models\SIS\Enrollment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'valid_from'  => 'datetime',
        'valid_until' => 'datetime',
        'is_active'   => 'boolean',
        'max_uses'    => 'integer',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Whether this code is currently valid (active, within date range, not exhausted).
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        return ! $this->isExhausted();
    }

    /**
     * Whether the code has reached its maximum number of uses.
     */
    public function isExhausted(): bool
    {
        if (is_null($this->max_uses)) {
            return false;
        }

        return $this->enrollments()->whereNotNull('discount_code_id')->count() >= $this->max_uses;
    }

    /**
     * Apply the discount to a total and return the discounted amount.
     */
    public function apply(float $total): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $total * ($this->discount_value / 100);

            return max(0.0, $total - $discount);
        }

        // Flat amount discount
        return max(0.0, $total - (float) $this->discount_value);
    }
}
