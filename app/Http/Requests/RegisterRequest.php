<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Combine first_name + surname into name for backward compatibility
        if ($this->filled('first_name') || $this->filled('surname')) {
            $combined = trim(($this->input('first_name') ?? '') . ' ' . ($this->input('surname') ?? ''));
            if ($combined) {
                $this->merge(['name' => $combined]);
            }
        }
    }

    public function rules()
    {
        $isAuthRegistration = $this->filled('password') && ! $this->filled('pricing_item_id');

        return [
            'name' => 'required|string|max:255',
            'first_name' => 'required_without:name|string|max:128',
            'surname' => 'required_without:name|string|max:128',
            'email' => 'required|email|max:255',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:1048',
            'is_member' => 'sometimes|boolean',
            'membership_number' => 'nullable|string|max:64',
            'pricing_item_id' => $isAuthRegistration ? 'nullable|integer|exists:pricing_items,id' : 'required|integer|exists:pricing_items,id',
            'password' => 'nullable|string|confirmed|min:8',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $secret = config('services.turnstile.secret');

            // Turnstile is optional in local/test; enforce only when configured.
            if (! $secret) {
                return;
            }

            $token = $this->input('cf-turnstile-response');
            if (! $token) {
                $validator->errors()->add('captcha', 'CAPTCHA validation is required.');
                return;
            }

            $response = Http::asForm()->timeout(5)->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $this->ip(),
                ]
            );

            if (! $response->successful() || ! ($response->json('success') ?? false)) {
                $validator->errors()->add('captcha', 'CAPTCHA verification failed. Please try again.');
            }
        });
    }
}
