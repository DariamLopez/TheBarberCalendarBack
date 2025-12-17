<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitRequest extends FormRequest
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
            'status' => ['sometimes', 'string'],
            'tax' =>    ['sometimes', 'numeric', 'max_digits:2', 'min:0'],
            'discount' => ['sometimes', 'numeric', 'max_digits:2', 'min:0'],
            'amount_paid' => ['sometimes', 'numeric', 'max_digits:2', 'min:0'],
            'notes' => ['sometimes', 'string', 'max:2000'],
        ];
    }
}
