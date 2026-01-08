<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|min:3|max:100',
            'email' => 'nullable|email:rfc,dns|max:100',
            'phone' => 'required|string|min:10|max:15',  // minimal 10 digit, max 15 (cukup longgar untuk Indonesia)
            'g-recaptcha-response' => 'required|string',  // token captcha
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.min'      => 'Nomor WhatsApp minimal 10 digit.',
            'phone.max'      => 'Nomor WhatsApp maksimal 15 digit.',
            'g-recaptcha-response.required' => 'Verifikasi captcha diperlukan.',
        ];
    }
}