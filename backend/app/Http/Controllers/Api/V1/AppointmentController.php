<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BookAppointmentRequest;
use App\Services\V1\AppointmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private AppointmentService $appointmentService)
    {
    }

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->query('status'),
            'booking_date' => $request->query('booking_date'),
            'date_from' => $request->query('date_from'),
            'date_to' => $request->query('date_to'),
            'search' => $request->query('search'),
            'page' => $request->query('page', 1),
            'per_page' => $request->query('per_page', 15),
        ];

        $result = $this->appointmentService->getAppointments(array_filter($filters));

        return $this->success(
            $result['data'],
            'Appointments retrieved successfully',
            200,
            ['pagination' => $result['pagination']]
        );
    }

    public function store(BookAppointmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->createAppointment($request->validated());

            return $this->success(
                $appointment,
                'Appointment created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        }
    }
}
