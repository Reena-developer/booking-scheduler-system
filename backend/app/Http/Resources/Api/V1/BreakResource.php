<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('H:i') : null,
            'end_time'   => $this->end_time   ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('H:i') : null,

        ];
    }
}
