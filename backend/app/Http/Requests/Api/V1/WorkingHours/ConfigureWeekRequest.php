<?php

namespace App\Http\Requests\Api\V1\WorkingHours;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ConfigureWeekRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'working_hours' => 'required|array|size:7',
            'working_hours.*.day_of_week' => 'required|integer|between:0,6|distinct',
            'working_hours.*.is_active' => 'required|boolean',
            'working_hours.*.start_time' => 'nullable|required_if:working_hours.*.is_active,true|date_format:H:i',
            'working_hours.*.end_time'   => 'nullable|required_if:working_hours.*.is_active,true|date_format:H:i|after:working_hours.*.start_time',
            'working_hours.*.breaks' => 'nullable|array',
            'working_hours.*.breaks.*.start_time' => 'required|date_format:H:i',
            'working_hours.*.breaks.*.end_time' => 'required|date_format:H:i|after:working_hours.*.breaks.*.start_time',
            'working_hours.*.breaks.*.title' => 'nullable|string|max:255',
            'working_hours.*.breaks.*.comment' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'working_hours.required' => 'Working hours configuration is required.',
            'working_hours.size' => 'Working hours must contain exactly 7 days.',
            'working_hours.*.day_of_week.required' => 'Day of week is required.',
            'working_hours.*.day_of_week.integer' => 'Day of week must be a number.',
            'working_hours.*.day_of_week.between' => 'Day of week must be between 0 (Sunday) and 6 (Saturday).',
            'working_hours.*.day_of_week.distinct' => 'Duplicate day of week entries are not allowed.',
            'working_hours.*.is_active.required' => 'is_active flag is required for each day.',
            'working_hours.*.is_active.boolean' => 'is_active must be true or false.',
            'working_hours.*.start_time.required_if' => 'Start time is required when the day is active.',
            'working_hours.*.start_time.date_format' => 'Start time must be in H:i format.',
            'working_hours.*.end_time.required_if' => 'End time is required when the day is active.',
            'working_hours.*.end_time.date_format' => 'End time must be in H:i format.',
            'working_hours.*.end_time.after' => 'End time must be after the start time.',
            'working_hours.*.breaks.array' => 'Breaks must be a valid list.',
            'working_hours.*.breaks.*.start_time.required' => 'Break start time is required.',
            'working_hours.*.breaks.*.start_time.date_format' => 'Break start time must be in H:i format.',
            'working_hours.*.breaks.*.end_time.required' => 'Break end time is required.',
            'working_hours.*.breaks.*.end_time.date_format' => 'Break end time must be in H:i format.',
            'working_hours.*.breaks.*.end_time.after' => 'Break end time must be after break start time.',
            'working_hours.*.breaks.*.title.string' => 'Break title must be a valid string.',
            'working_hours.*.breaks.*.title.max' => 'Break title must not exceed 255 characters.',
            'working_hours.*.breaks.*.comment.string' => 'Break comment must be a string.'
        ];
    }
}
