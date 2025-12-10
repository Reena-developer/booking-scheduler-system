<?php

namespace App\Http\Requests\Api\V1;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class GetAvailableSlotsRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'service_id' => 'nullable|exists:services,id'
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Date is required',
            'date.date_format' => 'Date must be in YYYY-MM-DD format',
            'date.after_or_equal' => 'Date must be today or later',
            'duration.required' => 'Service duration is required',
            'duration.integer' => 'Duration must be a number',
            'duration.min' => 'Duration must be at least 15 minutes',
            'duration.max' => 'Duration cannot exceed 480 minutes'
        ];
    }
}