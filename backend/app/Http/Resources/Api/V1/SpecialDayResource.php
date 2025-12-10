<?php 
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialDayResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'day_of_week' => $this->day_of_week,
            'type' => $this->type,
            'half_day_type' => $this->half_day_type,
            'date' => $this->date?->format('Y-m-d'),
            'start_time' => $this->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('H:i') : null,
            'end_time'   => $this->end_time   ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('H:i') : null,

            'title' => $this->title,
            'comment' => $this->comment,
            'is_active' => $this->is_active
        ];
    }
}