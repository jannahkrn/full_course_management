<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'user_id', 'status',
        'enrolled_at', 'completed_at', 'dropped_at',
    ];

    protected $casts = [
        'enrolled_at'  => 'datetime',
        'completed_at' => 'datetime',
        'dropped_at'   => 'datetime',
    ];

    public function scopeActive($query)     { return $query->where('status', 'active'); }
    public function scopeCompleted($query)  { return $query->where('status', 'completed'); }
    public function scopeDropped($query)    { return $query->where('status', 'dropped'); }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool    { return $this->status === 'active'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isDropped(): bool   { return $this->status === 'dropped'; }
}