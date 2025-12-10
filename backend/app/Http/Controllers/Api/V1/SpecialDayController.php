<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\WorkingHours\StoreSpecialDayRequest;
use App\Http\Resources\Api\V1\SpecialDayResource;
use App\Models\ProviderBreakTime;
use App\Services\V1\SpecialDayService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SpecialDayController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly SpecialDayService $specialDayService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['page', 'per_page', 'sort_by', 'sort_order', 'search', 'status']);
        $specialDates = $this->specialDayService->getSpecialDays($filters);

        return $this->paginated($specialDates, 'Special days fetched successfully', SpecialDayResource::class);
    }

    public function store(StoreSpecialDayRequest $request)
    {
        $data = $this->specialDayService->create($request->validated());
        return $this->success(new SpecialDayResource($data), 'Special day created successfully', 201);
    }

    public function destroy(ProviderBreakTime $special_day)
    {
        $this->specialDayService->deleteSpecialDay($special_day);
        return $this->success(null, 'Special day deleted successfully');
    }
}
