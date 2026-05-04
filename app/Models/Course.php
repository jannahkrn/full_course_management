<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'code', 'category_id', 'description',
        'language', 'department', 'department_url', 'thumbnail',
        'video_url', 'template_course_id', 'access_type',
        'is_registered', 'is_allowed', 'subscription_type',
        'allow_unsubscribe', 'storage_limit_mb', 'is_special',
        'tags', 'is_active', 'published_at',
    ];

    protected $casts = [
        'is_registered'      => 'boolean',
        'is_allowed'         => 'boolean',
        'allow_unsubscribe'  => 'boolean',
        'is_special'         => 'boolean',
        'is_active'          => 'boolean',
        'tags'               => 'array',
        'published_at'       => 'datetime',
        'storage_limit_mb'   => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(5);
            }
        });
    }

    // ─── Scopes ───────────────────────────────────────────────────────
    public function scopeActive($query)     { return $query->where('is_active', true); }
    public function scopePublished($query)  { return $query->whereNotNull('published_at'); }
    public function scopeByLanguage($query, $lang) { return $query->where('language', $lang); }
    public function scopeByCategory($query, $id)   { return $query->where('category_id', $id); }
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    // ─── Relations ────────────────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function templateCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'template_course_id');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_teachers', 'course_id', 'user_id')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    public function primaryTeacher(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_teachers', 'course_id', 'user_id')
                    ->wherePivot('role', 'primary');
    }

    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withPivot('status', 'enrolled_at', 'completed_at', 'dropped_at')
                    ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)->where('status', 'active');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class)->orderBy('order');
    }

    public function sessionCategories(): HasMany
    {
        return $this->hasMany(SessionCategory::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(CourseActivityLog::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────
    public function getEnrolledCountAttribute(): int
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at !== null;
    }
}