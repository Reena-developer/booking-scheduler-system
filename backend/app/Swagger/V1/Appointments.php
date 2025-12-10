<?php

namespace App\Swagger\V1;


/**
 * @OA\Post(
 *     path="/appointments/book",
 *     operationId="bookAppointment",
 *     tags={"Appointments"},
 *     summary="Book a new appointment",
 *     description="Public endpoint for clients to book an appointment. No authentication required.",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Appointment booking details",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"service_id", "client_name", "client_email", "booking_date", "start_time", "end_time"},
 *
 *             @OA\Property(property="service_id", type="integer", example=1),
 *             @OA\Property(property="client_name", type="string", example="Reena Hathaliya", maxLength=100),
 *             @OA\Property(property="client_email", type="string", format="email", example="reena@gmail.com", maxLength=100),
 *             @OA\Property(property="client_phone", type="string", nullable=true, example="+1234567890", maxLength=20),
 *             @OA\Property(property="booking_date", type="string", format="date", example="2025-12-20"),
 *             @OA\Property(property="start_time", type="string", format="time", example="10:00"),
 *             @OA\Property(property="end_time", type="string", format="time", example="11:00"),
 *             @OA\Property(property="notes", type="string", nullable=true, example="I prefer morning appointments")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Appointment booked successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Appointment booked successfully. Awaiting confirmation."),
 *             @OA\Property(property="data", ref="#/components/schemas/Appointment")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="This time slot is already booked"),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/appointments",
 *     operationId="listAppointments",
 *     tags={"Appointments"},
 *     summary="List all appointments",
 *     description="Admin endpoint to view all booked appointments with filtering and pagination.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"pending","confirmed","cancelled","completed"})),
 *     @OA\Parameter(name="booking_date", in="query", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="date_from", in="query", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="date_to", in="query", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", default=1)),
 *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", default=15)),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Appointments retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Appointments retrieved successfully"),
 *              @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/Appointment")
 *                 ),
 *                 @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
 *             )
 *          )
 *         )
 *     ),
 *
 *     @OA\Response(response=401, description="Unauthorized"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 * 
 * @OA\Schema(
 *     schema="Appointment",
 *     type="object",
 *     title="Appointment",
 *     required={"id","service_id","client_name","client_email","booking_date","start_time","end_time","status"},
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="service_id", type="integer", example=1),
 *     @OA\Property(property="service_name", type="string", example="Hair Cut"),
 *
 *     @OA\Property(property="client_name", type="string", example="John Doe"),
 *     @OA\Property(property="client_email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="client_phone", type="string", nullable=true, example="+1234567890"),
 *
 *     @OA\Property(property="booking_date", type="string", format="date", example="2025-12-20"),
 *     @OA\Property(property="day_name", type="string", example="Saturday"),
 *     @OA\Property(property="start_time", type="string", example="10:00"),
 *     @OA\Property(property="end_time", type="string", example="11:00"),
 *     @OA\Property(property="duration_minutes", type="integer", example=60),
 *
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"pending","confirmed","cancelled","completed"},
 *         example="pending"
 *     ),
 *
 *     @OA\Property(
 *         property="status_badge",
 *         type="object",
 *         @OA\Property(property="color", type="string", example="warning"),
 *         @OA\Property(property="text", type="string", example="Pending")
 *     ),
 *
 *     @OA\Property(property="notes", type="string", nullable=true, example="Client prefers morning"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Appointments {}

