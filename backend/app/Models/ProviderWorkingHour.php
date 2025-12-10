<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderWorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['day_name'];

    public const WEEK_DAYS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function getDayNameAttribute()
    {
        return self::WEEK_DAYS[$this->day_of_week] ?? null;
    }

    public function breaks()
    {
        return $this->hasMany(ProviderBreakTime::class, 'day_of_week', 'day_of_week')
            ->where('date', null);
    }

    public function breaksForDate($date)
    {
        return $this->hasMany(ProviderBreakTime::class, 'day_of_week', 'day_of_week')
                    ->where(function($q) use ($date) {
                        $q->whereNull('date')   
                        ->orWhere('date', $date);
                    })
                    ->where('is_active', true);
    }
}
