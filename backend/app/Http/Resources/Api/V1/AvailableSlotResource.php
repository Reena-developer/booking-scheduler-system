<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableSlotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total_slots' => $this['total_slots'],
            'service_duration' => $this['service_duration'],
            'slots' => $this['slots']
        ];
    }
}
