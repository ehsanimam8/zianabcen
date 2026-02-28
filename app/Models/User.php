<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUuids, Notifiable, \Spatie\Permission\Traits\HasRoles, \Spatie\Activitylog\Traits\LogsActivity;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected static function booted()
    {
        static::creating(function (User $user) {
            if (empty($user->roll_number)) {
                $user->roll_number = 'ZC-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $isAdmin = $this->hasRole(['Super Admin', 'Admin', 'admin', 'super_admin']) || str_ends_with($this->email, '@zainab.com');

        if ($panel->getId() === 'admin') {
            return $isAdmin;
        }

        if ($panel->getId() === 'teacher') {
            return $isAdmin || $this->hasRole(['Instructor', 'instructor', 'Teacher', 'teacher']) || str_starts_with($this->email, 'instructor') || str_starts_with($this->email, 'teacher');
        }

        return true;
    }

    public function getPrivacyNameAttribute()
    {
        if ($this->hasRole(['Student', 'student'])) {
            return 'Student (' . ($this->roll_number ?? 'Unknown') . ')';
        }
        return ($this->roles->first()?->name ?? 'Staff') . ' ' . $this->name;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'gender',
        'previous_secular_education',
        'previous_religious_education',
        'timezone',
        'roll_number',
        'profile_photo_url',
        'stripe_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function notes()
    {
        return $this->morphMany(\App\Models\CRM\Note::class, 'notable');
    }
}
