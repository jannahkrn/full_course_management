<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'avatar', 'phone', 'department', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function scopeAdmins($query)    { return $query->where('role', 'admin'); }
    public function scopeTeachers($query)  { return $query->where('role', 'teacher'); }
    public function scopeStudents($query)  { return $query->where('role', 'student'); }
    public function scopeActive($query)    { return $query->where('is_active', true); }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    public function teachingCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teachers', 'user_id', 'course_id')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->withPivot('status', 'enrolled_at', 'completed_at', 'dropped_at')
                    ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(CourseActivityLog::class);
    }
}