<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi!',
            'name.min' => 'Nama minimal terdiri dari 3 karakter.',
            'email.required' => 'Email tidak boleh kosong!',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok!'
        ];
    }
}