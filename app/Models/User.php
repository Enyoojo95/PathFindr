<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_STUDENT     = 'student';
    const ROLE_ADMIN       = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'school_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_suspended'      => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function testimonial()
    {
        return $this->hasOne(\App\Models\Testimonial::class);
    }

    // ── Role checks ────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    // Any kind of admin (school admin OR super admin)
    public function hasAdminAccess(): bool
    {
        return !$this->is_suspended
            && in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN]);
    }

    // ── Avatar ─────────────────────────────────────────────────
    public function avatarUrl(): string
    {
        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=1A2A6C&color=F5A623&bold=true&size=128';
    }
}
