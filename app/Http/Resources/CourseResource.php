<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'slug'              => $this->slug,
            'code'              => $this->code,
            'description'       => $this->description,
            'language'          => $this->language,
            'department'        => $this->department,
            'department_url'    => $this->department_url,
            'thumbnail'         => $this->thumbnail,
            'video_url'         => $this->video_url,
            'access_type'       => $this->access_type,
            'is_registered'     => $this->is_registered,
            'is_allowed'        => $this->is_allowed,
            'subscription_type' => $this->subscription_type,
            'allow_unsubscribe' => $this->allow_unsubscribe,
            'storage_limit_mb'  => $this->storage_limit_mb,
            'is_special'        => $this->is_special,
            'tags'              => $this->tags ?? [],
            'is_active'         => $this->is_active,
            'is_published'      => $this->is_published,
            'published_at'      => $this->published_at?->toDateTimeString(),
            'created_at'        => $this->created_at?->toDateTimeString(),
            'updated_at'        => $this->updated_at?->toDateTimeString(),

            // Relations (when loaded)
            'category'          => new CourseCategoryResource($this->whenLoaded('category')),
            'teachers'          => UserResource::collection($this->whenLoaded('teachers')),
            'enrolled_count'    => $this->when(
                $this->relationLoaded('enrollments'),
                fn() => $this->enrollments->where('status', 'active')->count()
            ),
            'sessions'          => CourseSessionResource::collection($this->whenLoaded('sessions')),
        ];
    }
}