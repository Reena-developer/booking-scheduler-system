<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProviderBreakTime;

class ProviderBreakTimeSeeder extends Seeder
{
    public function run(): void
    {
        for ($day = 1; $day <= 5; $day++) {
            ProviderBreakTime::updateOrCreate(
                ['day_of_week' => $day, 'title' => 'Lunch Break'],
                [
                    'start_time' => '13:00:00',
                    'end_time' => '14:00:00'
                ]
            );
        }
    }
}
