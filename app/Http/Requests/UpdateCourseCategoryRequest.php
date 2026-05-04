<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('category') instanceof \App\Models\CourseCategory
            ? $this->route('category')->id
            : $this->route('category');

        return [
            'name'        => "sometimes|required|string|max:100|unique:course_categories,name,{$id}",
            'description' => 'sometimes|nullable|string',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}