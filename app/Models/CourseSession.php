<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class CourseSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['course_id', 'title', 'description', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'session_id')->orderBy('order');
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(CourseExercise::class, 'session_id')->orderBy('order');
    }
}