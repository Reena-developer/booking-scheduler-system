<?php 
namespace App\Services\V1;

use App\Models\ProviderBreakTime;
use Carbon\Carbon;

class SpecialDayService
{
    public function getSpecialDays(array $filters = [])
    {
        $query = ProviderBreakTime::query();
        $query->specialDays();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }
        
        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status']);
        }
        
        $sortBy = $filters['sort_by'] ?? 'date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        $perPage = min($filters['per_page'] ?? 10, 100);
        $page = $filters['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        if (!empty($data['date'])) {
            $data['day_of_week'] = Carbon::createFromFormat('Y-m-d', $data['date'])->dayOfWeek;
        }
        if (!empty($data['type']) && $data['type'] === ProviderBreakTime::TYPE_FULL_OFF) {
            $data['start_time'] =  config('constants.booking.full_off_start');
            $data['end_time'] =  config('constants.booking.full_off_end');
        }
        return ProviderBreakTime::create($data);
    }

    public function deleteSpecialDay(ProviderBreakTime $specialDay)
    {
        return $specialDay->delete();
    }
}
