<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Atur ke false atau logika otorisasi jika diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // Kembalikan ke confirmed
            'password_confirmation' => 'required|string|max:255', // Tambahkan aturan eksplisit untuk password_confirmation
            'role' => 'required|in:student,company,admin',
            'full_name' => 'required_if:role,student|string|max:255',
            'company_name' => 'required_if:role,company|string|max:255',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'full_name.required_if' => 'The full name field is required when role is student.',
            'company_name.required_if' => 'The company name field is required when role is company.',
        ];
    }
}