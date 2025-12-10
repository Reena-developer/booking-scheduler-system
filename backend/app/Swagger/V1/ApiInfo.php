<?php

namespace App\Swagger\V1;

/**
 * Root OpenAPI/Swagger specification for V1 API
 *
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Smart Booking Scheduler API - V1",
 *         description="Complete API documentation for Smart Booking Scheduler. Comprehensive system for managing services, bookings, and scheduling.",
 *         termsOfService="https://example.com/terms",
 *         @OA\Contact(
 *             name="API Support",
 *             email="rdhathaliya411@gmail.com",
 *             url="https://example.com/support"
 *         ),
 *         @OA\License(
 *             name="MIT",
 *             url="https://opensource.org/licenses/MIT"
 *         )
 *     ),
 *
 *     @OA\Server(
 *         url="http://localhost:8000/api/v1",
 *         description="Local Development Server"
 *     ),
 *     @OA\Server(
 *         url="https://api.example.com/api/v1",
 *         description="Production Server"
 *     ),
 *
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             type="http",
 *             description="Login with email and password to get the authentication token",
 *             name="Token based authentication",
 *             in="header",
 *             securityScheme="bearerAuth",
 *             scheme="bearer",
 *             bearerFormat="JWT"
 *         )
 *     ),
 *
 *     @OA\Tag(
 *         name="Auth",
 *         description="Authentication endpoints"
 *     ),
 *     @OA\Tag(
 *         name="Services",
 *         description="Service management endpoints"
 *     ),
 *     @OA\Tag(
 *         name="Bookings",
 *         description="Booking management endpoints"
 *     ),
 *     @OA\Tag(
 *          name="Working Hours", 
 *          description="Working hours configuration endpoints"
 *      ),
 *     @OA\Tag(
 *          name="Break Times", 
 *          description="Break times and special days management endpoints"
 *      )
 * )
 */
class ApiInfo
{
}