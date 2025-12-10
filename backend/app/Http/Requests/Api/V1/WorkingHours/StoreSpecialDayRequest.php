<?php

namespace App\Http\Requests\Api\V1\WorkingHours;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialDayRequest extends FormRequest
{
    use ApiResponseTrait;
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
            'date' => 'required|date_format:Y-m-d',
            'type' => 'required|in:full_off,half_day,extra_hours',
            'half_day_type' => 'nullable|in:am,pm|required_if:type,half_day',
            'start_time' => 'nullable|date_format:H:i|required_if:type,extra_hours',
            'end_time' => 'nullable|date_format:H:i|required_if:type,extra_hours|after:start_time',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Date is required',
            'date.date_format' => 'Date must be in Y-m-d format',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be one of: full_off, half_day, extra_hours',
            'half_day_type.required_if' => 'Half day type (am/pm) is required for half_day type',
            'start_time.required_if' => 'Start time is required for extra_hours type',
            'end_time.required_if' => 'End time is required for extra_hours type',
            'end_time.after' => 'End time must be after start time'
        ];
    }
}


