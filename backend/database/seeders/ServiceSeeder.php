<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Haircut', 'duration' => 30, 'price' => 1500, 'is_active' => true],
            ['name' => 'Beard Trim', 'duration' => 20, 'price' => 1000, 'is_active' => true],
            ['name' => 'Shampoo & Style', 'duration' => 45, 'price' => 2500, 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
