<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderBreakTime extends Model
{
    protected $fillable = [
        'day_of_week',
        'date',
        'start_time',
        'end_time',
        'title',
        'comment',
        'is_active',
        'type',
        'half_day_type'
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    const TYPE_BREAK = 'break';
    const TYPE_FULL_OFF = 'full_off';
    const TYPE_EXTRA_HOURS = 'extra_hours_off';


    public function scopeSpecialDays($query)
    {
        return $query->whereNotNull('date');
    }

}
