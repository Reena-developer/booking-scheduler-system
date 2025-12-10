<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\WorkingHours\ConfigureWeekRequest;
use App\Http\Requests\Api\V1\WorkingHours\ConfigureDayRequest;
use App\Services\V1\WeeklyScheduleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WorkingHoursController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private WeeklyScheduleService $weeklyScheduleService) {}

    /**
     * Configure entire week at once
     * 
     * @param ConfigureWeekRequest $request
     * @return JsonResponse
     */
    public function configureWeek(ConfigureWeekRequest $request)
    {
        $result = $this->weeklyScheduleService->configureWeek($request->validated());
        
        if ($result['has_errors']) {
            return $this->error(
                'Some days failed to configure',
                422,
                ['errors' => $result['errors'], 'successful' => $result['successful']]
            );
        }
        
        return $this->success(
            $result['successful'],
            'Weekly schedule configured successfully',
            200
        );
    }

    public function configureSingleDay(ConfigureDayRequest $request)
    {
        try {
            $result = $this->weeklyScheduleService->configureDay($request->validated());
            
            return $this->success(
                $result,
                'Working hours updated successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        }
    }

    public function getWeeklySchedule(Request $request)
    {
        try {
            $result = $this->weeklyScheduleService->getWeeklySchedule();
            
            return $this->success(
                $result,
                'Weekly schedule retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }
}