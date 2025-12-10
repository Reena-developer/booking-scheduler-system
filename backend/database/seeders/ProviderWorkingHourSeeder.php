<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProviderWorkingHour;

class ProviderWorkingHourSeeder extends Seeder
{
    public function run(): void
    {
        for ($day = 1; $day <= 5; $day++) {
            ProviderWorkingHour::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00'
                ]
            );
        }
    }
}
