<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseExercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id', 'title', 'description', 'type',
        'duration_minutes', 'max_score', 'passing_score',
        'open_at', 'close_at', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'max_score'    => 'decimal:2',
        'passing_score'=> 'decimal:2',
        'open_at'      => 'datetime',
        'close_at'     => 'datetime',
    ];

    public function scopeActive($query) { return $query->where('is_active', true); }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'session_id');
    }
}