<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'service_name' => $this->service?->name,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_phone' => $this->client_phone,
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'day_name' => $this->booking_date->format('l'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration_minutes' => $this->calculateDuration(),
            'status' => $this->status,
            'status_badge' => $this->getStatusBadge(),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function calculateDuration(): int
    {
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);
        return $end->diffInMinutes($start);
    }

    private function getStatusBadge(): array
    {
        $badges = [
            'pending' => ['color' => 'warning', 'text' => 'Pending'],
            'confirmed' => ['color' => 'success', 'text' => 'Confirmed'],
            'cancelled' => ['color' => 'danger', 'text' => 'Cancelled'],
            'completed' => ['color' => 'info', 'text' => 'Completed'],
        ];
        return $badges[$this->status] ?? ['color' => 'secondary', 'text' => 'Unknown'];
    }
}