<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GetAvailableSlotsRequest;
use App\Http\Resources\Api\V1\AvailableSlotResource;
use App\Services\V1\AvailableSlotsService;
use App\Traits\ApiResponseTrait;

class AvailableSlotsController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private AvailableSlotsService $slotsService){}
    
    public function index(GetAvailableSlotsRequest $request)
    {
        try {
            $date = $request->validated('date');
            $serviceId = $request->validated('service_id');
            $data = $this->slotsService->getAvailableSlots($date, $serviceId);
            
            return $this->success(
                new AvailableSlotResource($data),
                "Slots fetched successfully",
                200
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                400
            );
        }
    }
}