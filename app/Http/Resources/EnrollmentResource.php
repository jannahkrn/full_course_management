<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'course_id'    => $this->course_id,
            'user_id'      => $this->user_id,
            'status'       => $this->status,
            'enrolled_at'  => $this->enrolled_at?->toDateTimeString(),
            'completed_at' => $this->completed_at?->toDateTimeString(),
            'dropped_at'   => $this->dropped_at?->toDateTimeString(),
            'course'       => new CourseResource($this->whenLoaded('course')),
            'user'         => new UserResource($this->whenLoaded('user')),
        ];
    }
}