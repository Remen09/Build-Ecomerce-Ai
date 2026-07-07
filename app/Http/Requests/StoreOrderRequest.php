<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_phone.regex' => 'The phone number format is invalid.',
        ];
    }
}
