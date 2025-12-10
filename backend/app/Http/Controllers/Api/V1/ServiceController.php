<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Service\StoreServiceRequest;
use App\Http\Requests\Api\V1\Service\UpdateServiceRequest;
use App\Http\Resources\Api\V1\ServiceResource;
use App\Models\Service;
use App\Services\V1\ServiceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ServiceService $serviceService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['page', 'per_page', 'sort_by', 'sort_order', 'search', 'status']);
        $services = $this->serviceService->list($filters);

        return $this->paginated($services, 'Services fetched successfully', ServiceResource::class);
    }

    public function store(StoreServiceRequest $request)
    {
        $service = $this->serviceService->create($request->validated());
        return $this->success(new ServiceResource($service), 'Service created successfully', 201);
    }

    public function show(Service $service)
    {
        return $this->success(new ServiceResource($service), 'Service details fetched');
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $updated = $this->serviceService->update($service, $request->validated());
        return $this->success(new ServiceResource($updated), 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        $this->serviceService->delete($service);
        return $this->success(null, 'Service deleted successfully');
    }
}
