<?php

namespace App\Http\Requests;

use App\Enums\SalaryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateWorkerRequest extends FormRequest
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
            'role' => ['nullable', 'string', 'max:20', 'min:3'],
            'salary_type' => ['required', 'string'],
            'salary_amount' => ['nullable', 'numeric'],
            'commission_rate' => ['nullable', 'numeric', 'max_digits:2'],
            'is_active' => ['sometimes', 'boolean'],
            'service_category_id' => ['sometimes', 'exists:service_categories,id'],
        ];
    }
}
