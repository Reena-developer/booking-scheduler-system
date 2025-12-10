<?php

namespace App\Http\Requests\Api\V1\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponseTrait;

class StoreServiceRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'name.max' => 'The service name cannot exceed 255 characters.',
            'duration.required' => 'The duration is required.',
            'duration.min' => 'The duration must be at least 1 minute.',
            'price.numeric' => 'The price must be a valid number.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Service Name',
            'duration' => 'Service Duration',
            'price' => 'Service Price',
        ];
    }
}