<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class CourseActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'user_id', 'action',
        'loggable_id', 'loggable_type',
        'meta', 'accessed_at',
    ];

    protected $casts = [
        'meta'        => 'array',
        'accessed_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}