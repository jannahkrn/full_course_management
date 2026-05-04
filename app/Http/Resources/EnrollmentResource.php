<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'session_id'       => $this->session_id,
            'title'            => $this->title,
            'description'      => $this->description,
            'type'             => $this->type,
            'duration_minutes' => $this->duration_minutes,
            'max_score'        => $this->max_score,
            'passing_score'    => $this->passing_score,
            'open_at'          => $this->open_at?->toDateTimeString(),
            'close_at'         => $this->close_at?->toDateTimeString(),
            'order'            => $this->order,
            'is_active'        => $this->is_active,
        ];
    }
}