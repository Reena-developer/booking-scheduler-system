<?php

namespace App\Http\Requests\Api\V1\Service;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'sometimes|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'The service name cannot exceed 255 characters.',
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