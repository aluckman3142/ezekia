<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                Rule::unique('users'),
                'required',
                'string',
                'min:5',
                'max:255',
                'email'
            ],
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'hourly_rate' => [
                'numeric',
            ],
            'default_currency' => [
                'required',
                Rule::in(['GBP','USD','EUR']),
            ]
        ];
    }
}
