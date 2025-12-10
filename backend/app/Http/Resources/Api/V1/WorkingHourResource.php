<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkingHourResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'day_of_week' => $this->day_of_week,
            'day_name'    => $this->day_name,
            'start_time' => $this->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('H:i') : null,
            'end_time'   => $this->end_time   ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('H:i') : null,

            'is_day_off'  => !$this->is_active,
            'breaks'      => BreakResource::collection($this->breaks),
        ];
    }
}
