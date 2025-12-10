@component('mail::message')
# Appointment Confirmed

Hello {{ $appointment->client_name }},

Your appointment has been successfully booked.

**Details:**

- Service: {{ $appointment->service->name }}
- Date: {{ $appointment->booking_date }}
- Time: {{ $appointment->start_time }} - {{ $appointment->end_time }}

Thanks for choosing us!

Regards,<br>
{{ config('app.name') }}
@endcomponent
