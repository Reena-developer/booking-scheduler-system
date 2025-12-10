<?php 
namespace App\Http\Requests\Api\V1\WorkingHours;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class ConfigureDayRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|between:0,6',
            'is_active'   => 'required|boolean',
            'start_time'  => 'nullable|required_if:is_active,true|date_format:H:i',
            'end_time'    => 'nullable|required_if:is_active,true|date_format:H:i|after:start_time',
            'breaks' => 'nullable|array',
            'breaks.*.start_time' => 'required|date_format:H:i',
            'breaks.*.end_time'   => 'required|date_format:H:i|after:breaks.*.start_time',
            'breaks.*.title'      => 'nullable|string|max:255',
            'breaks.*.comment'    => 'nullable|string',
        ];
    }

        public function messages(): array
    {
        return [
            'day_of_week.required' => 'Day of week is required.',
            'day_of_week.integer'  => 'Day of week must be a number.',
            'day_of_week.between'  => 'Day of week must be between 0 (Sunday) and 6 (Saturday).',
            'is_active.required' => 'is_active field is required.',
            'is_active.boolean'  => 'is_active must be true or false.',
            'start_time.required_if' => 'Start time is required when the day is active.',
            'start_time.date_format' => 'Start time must be in H:i format.',
            'end_time.required_if' => 'End time is required when the day is active.',
            'end_time.date_format' => 'End time must be in H:i format.',
            'end_time.after'       => 'End time must be after the start time.',
            'breaks.array' => 'Breaks must be a valid list.',
            'breaks.*.start_time.required'     => 'Break start time is required.',
            'breaks.*.start_time.date_format'  => 'Break start time must be in H:i format.',
            'breaks.*.end_time.required'     => 'Break end time is required.',
            'breaks.*.end_time.date_format'  => 'Break end time must be in H:i format.',
            'breaks.*.end_time.after'        => 'Break end time must be after break start time.',
            'breaks.*.title.string' => 'Break title must be a valid string.',
            'breaks.*.title.max'    => 'Break title must not exceed 255 characters.',
            'breaks.*.comment.string' => 'Break comment must be a string.',
        ];
    }


}