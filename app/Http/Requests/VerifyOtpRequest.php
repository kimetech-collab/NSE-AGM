<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'registration_id' => 'required|integer|exists:registrations,id',
            'otp' => 'required|string|size:6',
        ];
    }
}
