<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'course_id'   => $this->course_id,
            'title'       => $this->title,
            'description' => $this->description,
            'order'       => $this->order,
            'is_active'   => $this->is_active,
            'materials'   => CourseMaterialResource::collection($this->whenLoaded('materials')),
            'exercises'   => CourseExerciseResource::collection($this->whenLoaded('exercises')),
        ];
    }
}