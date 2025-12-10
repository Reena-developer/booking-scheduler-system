<?php

namespace App\Services\V1;

use App\Http\Resources\Api\V1\WorkingHourResource;
use App\Models\ProviderWorkingHour;
use App\Models\ProviderBreakTime;
use Illuminate\Support\Facades\DB;
use Exception;

class WeeklyScheduleService
{
    public function configureWeek(array $data)
    {
        $successful = [];
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($data['working_hours'] as $dayConfig) {
                try {
                    $successful[] = $this->configureDay($dayConfig);
                } catch (Exception $e) {
                    $errors[] = [
                        'day_of_week' => $dayConfig['day_of_week'] ?? 'unknown',
                        'error' => $e->getMessage(),
                    ];
                }
            }
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to configure weekly schedule: {$e->getMessage()}");
        }
        
        return [
            'successful' => $successful,
            'errors' => $errors,
            'has_errors' => !empty($errors),
        ];
    }
    
    public function configureDay(array $dayConfig)
    {
        $dayOfWeek = $dayConfig['day_of_week'];
        $breaks = $dayConfig['breaks'] ?? [];

        $hourData = $dayConfig;
        unset($hourData['breaks']);
        
        $workingHour = ProviderWorkingHour::updateOrCreate(
            ['day_of_week' => $dayOfWeek],
            $hourData
        );

        $breakIds = [];
        foreach ($breaks as $break) {
            $breakRecord = ProviderBreakTime::updateOrCreate(
                [
                    'day_of_week' => $dayOfWeek,
                    'start_time' => $break['start_time'],
                    'end_time' => $break['end_time'],
                ],
                [
                    'title' => $break['title'] ?? null,
                    'comment' => $break['comment'] ?? null,
                    'is_active' => true,
                ]
            );
            $breakIds[] = $breakRecord->id;
        }
        
        ProviderBreakTime::where('day_of_week', $dayOfWeek)
            ->whereNull('date')
            ->whereNotIn('id', $breakIds)
            ->delete();
        
        return new WorkingHourResource($workingHour->fresh()->load('breaks'));
    }

    public function getWeeklySchedule()
    {
        $workingHours = ProviderWorkingHour::with('breaks')->get()->keyBy('day_of_week');

        $weeklySchedule = [];

        foreach (ProviderWorkingHour::WEEK_DAYS as $day => $dayName) {
            if (isset($workingHours[$day])) {
                $weeklySchedule[] = new WorkingHourResource($workingHours[$day]);
            } else {
                $weeklySchedule[] = [
                    'id' => null,
                    'day_of_week' => $day,
                    'day_name' => $dayName,
                    'is_active' => false,
                    'start_time' => null,
                    'end_time' => null,
                    'breaks' => [],
                    'created_at' => null,
                    'updated_at' => null,
                ];
            }
        }

        return $weeklySchedule;
    }

}