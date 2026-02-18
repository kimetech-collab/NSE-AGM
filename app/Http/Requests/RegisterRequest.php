<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'is_member' => 'sometimes|boolean',
            'membership_number' => 'nullable|string|max:64',
            'pricing_item_id' => 'required|integer|exists:pricing_items,id',
        ];
    }
}
