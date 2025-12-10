<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function booking_requires_all_fields()
    {
        $response = $this->postJson('/api/v1/appointments/book', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'service_id',
                'client_name',
                'client_email',
                'booking_date',
                'start_time',
                'end_time'
            ]);
    }

    #[Test]
    public function booking_can_be_created_successfully()
    {
        $service = Service::factory()->create();

        $payload = [
            'service_id' => $service->id,
            'client_name' => 'Reena Hathaliya',
            'client_email' => 'reena@gmail.com',
            'client_phone' => '+911234567890',
            'booking_date' => now()->addDays(1)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'notes' => 'Test booking',
        ];

        $response = $this->postJson('/api/v1/appointments/book', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Appointment created successfully',
            ]);

        $this->assertDatabaseHas('appointments', [
            'client_email' => 'reena@gmail.com',
            'service_id' => $service->id,
        ]);
    }

    #[Test]
    public function cannot_book_already_booked_slot()
    {
        $service = Service::factory()->create();

        // First booking
        $this->postJson('/api/v1/appointments/book', [
            'service_id' => $service->id,
            'client_name' => 'Existing User',
            'client_email' => 'existing@gmail.com',
            'client_phone' => '+911111111111',
            'booking_date' => now()->addDays(1)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        // Try to rebook same slot
        $response = $this->postJson('/api/v1/appointments/book', [
            'service_id' => $service->id,
            'client_name' => 'Reena',
            'client_email' => 'reena@gmail.com',
            'client_phone' => '+911222222222',
            'booking_date' => now()->addDays(1)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'This time slot is already booked',
            ]);
    }
}
