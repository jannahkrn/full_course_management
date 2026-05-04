<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    public function messages(): array
    {
        return [
            'user_ids.required'     => 'Pilih setidaknya satu pengguna.',
            'course_ids.required'   => 'Pilih setidaknya satu mata kuliah.',
            'user_ids.*.exists'     => 'Salah satu pengguna tidak ditemukan.',
            'course_ids.*.exists'   => 'Salah satu mata kuliah tidak ditemukan.',
        ];
    }
}