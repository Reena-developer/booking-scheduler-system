<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'description'=> $this->description,
            'duration'  => $this->duration,
            'price'     => $this->price,
            'is_active' => (bool)$this->is_active,
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
