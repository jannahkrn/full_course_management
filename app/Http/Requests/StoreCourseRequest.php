<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'code'              => 'nullable|string|max:50|regex:/^[a-zA-Z0-9]+$/|unique:courses,code',
            'category_id'       => 'nullable|exists:course_categories,id',
            'description'       => 'nullable|string',
            'language'          => 'nullable|string|max:10',
            'department'        => 'nullable|string|max:255',
            'department_url'    => 'nullable|url|max:500',
            'thumbnail'         => 'nullable|image|max:2048',
            'video_url'         => 'nullable|url|max:500',
            'template_course_id'=> 'nullable|exists:courses,id',
            'access_type'       => 'nullable|in:public,private,restricted',
            'is_registered'     => 'nullable|boolean',
            'is_allowed'        => 'nullable|boolean',
            'subscription_type' => 'nullable|in:allowed,teacher_only',
            'allow_unsubscribe' => 'nullable|boolean',
            'storage_limit_mb'  => 'nullable|integer|min:0',
            'is_special'        => 'nullable|boolean',
            'tags'              => 'nullable|array',
            'tags.*'            => 'string|max:50',
            'teacher_ids'       => 'nullable|array',
            'teacher_ids.*'     => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul mata kuliah wajib diisi.',
            'code.unique'          => 'Kode mata kuliah sudah digunakan.',
            'code.regex'           => 'Kode hanya boleh huruf (a-z) dan angka (0-9).',
            'category_id.exists'   => 'Kategori tidak ditemukan.',
            'teacher_ids.*.exists' => 'Salah satu guru tidak ditemukan.',
        ];
    }
}