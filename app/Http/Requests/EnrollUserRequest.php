<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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