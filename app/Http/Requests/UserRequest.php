<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan ini true agar form diizinkan lewat
    }

    // Menulis Validasi di Form Request[cite: 17]
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }

    // Custom Validation Message: Mengganti pesan default bahasa Inggris[cite: 17]
    public function messages()
    {
        return [
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'Email tidak boleh kosong!',
            'password.min' => 'Password harus minimal 6 karakter!'
        ];
    }
}