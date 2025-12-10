<?php

return [

    'booking' => [
        // Slot interval in minutes
        'interval' => env('BOOKING_INTERVAL', 10),

        // Full day off time range (when type='full_off')
        'full_off_start' => env('FULL_OFF_START', '00:00:00'),
        'full_off_end' => env('FULL_OFF_END', '23:59:59'),
    ],

];

