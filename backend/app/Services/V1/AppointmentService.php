<?php

namespace App\Services\V1;

use App\Http\Resources\Api\V1\AppointmentResource;
use App\Jobs\SendBookingConfirmation;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class AppointmentService
{
    public function createAppointment(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Check if time slot is available
            $existingAppointment = Appointment::where('booking_date', $data['booking_date'])
                ->where('start_time', $data['start_time'])
                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                ->first();

            if ($existingAppointment) {
                throw new Exception('This time slot is already booked');
            }

            $appointment = Appointment::create($data);
            SendBookingConfirmation::dispatch($appointment);

            DB::commit();

            return new AppointmentResource($appointment);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    public function getAppointments(array $filters = []): array
    {
        $query = Appointment::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['booking_date'])) {
            $query->whereDate('booking_date', $filters['booking_date']);
        }

        if (isset($filters['date_from'], $filters['date_to'])) {
            $query->whereBetween('booking_date', [
                $filters['date_from'],
                $filters['date_to'],
            ]);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%$search%")
                  ->orWhere('client_email', 'like', "%$search%")
                  ->orWhere('client_phone', 'like', "%$search%");
            });
        }

        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 15;

        $total = $query->count();
        $appointments = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => AppointmentResource::collection($appointments)->resolve(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total),
            ],
        ];
    }
}