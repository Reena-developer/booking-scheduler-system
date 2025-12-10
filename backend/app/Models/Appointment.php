<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'client_email',
        'client_name',
        'client_phone',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
