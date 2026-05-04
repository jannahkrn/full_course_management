<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// ─── Enrollment Requests ──────────────────────────────────────────────────────

class EnrollUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_ids.required'    => 'Pilih setidaknya satu pengguna.',
            'user_ids.*.exists'    => 'Salah satu pengguna tidak ditemukan.',
        ];
    }
}

class BulkEnrollRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'user_ids'    => 'required|array|min:1',
            'user_ids.*'  => 'exists:users,id',
            'course_ids'  => 'required|array|min:1',
            'course_ids.*'=> 'exists:courses,id',
        ];
    }
}

// ─── Category Requests ────────────────────────────────────────────────────────

class StoreCourseCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100|unique:course_categories,name',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ];
    }
}

class UpdateCourseCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id ?? $this->route('category');
        return [
            'name'        => "sometimes|required|string|max:100|unique:course_categories,name,{$categoryId}",
            'description' => 'sometimes|nullable|string',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}

// ─── Session Requests ─────────────────────────────────────────────────────────

class StoreCourseSessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }
}

class UpdateCourseSessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'order'       => 'sometimes|integer|min:0',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}

// ─── Auth Requests ────────────────────────────────────────────────────────────

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }
}

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'nullable|in:admin,teacher,student',
            'department' => 'nullable|string|max:255',
        ];
    }
}