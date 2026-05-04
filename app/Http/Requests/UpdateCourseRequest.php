<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id ?? $this->route('course');

        return [
            'title'             => 'sometimes|required|string|max:255',
            'code'              => "sometimes|nullable|string|max:50|regex:/^[a-zA-Z0-9]+$/|unique:courses,code,{$courseId}",
            'category_id'       => 'sometimes|nullable|exists:course_categories,id',
            'description'       => 'sometimes|nullable|string',
            'language'          => 'sometimes|nullable|string|max:10',
            'department'        => 'sometimes|nullable|string|max:255',
            'department_url'    => 'sometimes|nullable|url|max:500',
            'thumbnail'         => 'sometimes|nullable|image|max:2048',
            'video_url'         => 'sometimes|nullable|url|max:500',
            'template_course_id'=> 'sometimes|nullable|exists:courses,id',
            'access_type'       => 'sometimes|nullable|in:public,private,restricted',
            'is_registered'     => 'sometimes|nullable|boolean',
            'is_allowed'        => 'sometimes|nullable|boolean',
            'subscription_type' => 'sometimes|nullable|in:allowed,teacher_only',
            'allow_unsubscribe' => 'sometimes|nullable|boolean',
            'storage_limit_mb'  => 'sometimes|nullable|integer|min:0',
            'is_special'        => 'sometimes|nullable|boolean',
            'tags'              => 'sometimes|nullable|array',
            'tags.*'            => 'string|max:50',
            'teacher_ids'       => 'sometimes|nullable|array',
            'teacher_ids.*'     => 'exists:users,id',
            'is_active'         => 'sometimes|boolean',
        ];
    }
}