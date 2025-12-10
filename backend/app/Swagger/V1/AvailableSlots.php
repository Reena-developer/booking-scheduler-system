<?php

namespace App\Swagger\V1;


/**
 * @OA\Post(
 *     path="/available-slots",
 *     operationId="getAvailableSlots",
 *     tags={"Available Slots"},
 *     summary="Get available booking slots",
 *     description="Returns available appointment slots for a given date based on service duration.",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Date and optional service ID for fetching available slots",
 *         @OA\JsonContent(
 *             required={"date"},
 *             @OA\Property(property="date", type="string", format="date", example="2025-12-20", description="Appointment date in YYYY-MM-DD format"),
 *             @OA\Property(property="service_id", type="integer", example=1, description="Optional service reference ID")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Available slots retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Available slots retrieved successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/AvailableSlotsResponse")
 *         )
 *     ),
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 * 
 * @OA\Schema(
 *     schema="AvailableSlotsResponse",
 *     type="object",
 *     title="Available Slots Response",
 *     required={"service_duration", "slots", "total_slots"},
 *
 *     @OA\Property(property="service_duration", type="integer", example=60),
 *
 *     @OA\Property(
 *         property="slots",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"start_time", "end_time"},
 *             @OA\Property(property="start_time", type="string", example="09:00"),
 *             @OA\Property(property="end_time", type="string", example="10:00")
 *         )
 *     ),
 *
 *     @OA\Property(property="total_slots", type="integer", example=5)
 * )
 */

class AvailableSlots {}
