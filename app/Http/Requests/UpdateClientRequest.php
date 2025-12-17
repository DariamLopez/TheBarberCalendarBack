<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100', 'min:3'],
            'phone' => ['sometimes', 'string', 'max:14', 'unique:clients,phone', 'regex:/^(?:\+1\s?)?(?:\(?([2-9][0-9]{2})\)?[\s.-]?([0-9]{3})[\s.-]?([0-9]{4}))$/'],
            'address' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000']
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Invalid phone format'
        ];
    }
}
