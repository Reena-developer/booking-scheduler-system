<?php

namespace App\Swagger\V1;

/**
 * Services API endpoints - Full CRUD operations
 */

/**
 * @OA\Get(
 *     path="/services",
 *     operationId="listServices",
 *     tags={"Services"},
 *     summary="List all services",
 *     description="Retrieve a paginated list of all active services. Supports filtering, sorting, and searching.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(ref="#/components/parameters/Page"),
 *     @OA\Parameter(ref="#/components/parameters/PerPage"),
 *     @OA\Parameter(ref="#/components/parameters/SortBy"),
 *     @OA\Parameter(ref="#/components/parameters/SortOrder"),
 *     @OA\Parameter(ref="#/components/parameters/Search"),
 *     @OA\Parameter(ref="#/components/parameters/Status"),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Services fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Services fetched successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/Service")
 *                 ),
 *                 @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Post(
 *     path="/services",
 *     operationId="createService",
 *     tags={"Services"},
 *     summary="Create a new service",
 *     description="Create a new service with name, description, duration, and price.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Service data",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name", "duration"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 example="Hair Cut",
 *                 description="Service name"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 example="Professional haircut service",
 *                 description="Service description"
 *             ),
 *             @OA\Property(
 *                 property="duration",
 *                 type="integer",
 *                 minimum=1,
 *                 maximum=1440,
 *                 example=30,
 *                 description="Duration in minutes"
 *             ),
 *             @OA\Property(
 *                 property="price",
 *                 type="number",
 *                 format="float",
 *                 minimum=0,
 *                 example=299.00,
 *                 description="Service price"
 *             ),
 *             @OA\Property(
 *                 property="is_active",
 *                 type="boolean",
 *                 example=true,
 *                 description="Service active status"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Service created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service created successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Service")
 *         )
 *     ),
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Get(
 *     path="/services/{id}",
 *     operationId="getService",
 *     tags={"Services"},
 *     summary="Get a specific service",
 *     description="Retrieve detailed information about a specific service.",
 *
 *     @OA\Parameter(ref="#/components/parameters/Id"),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Service details fetched",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service details fetched"),
 *             @OA\Property(property="data", ref="#/components/schemas/Service")
 *         )
 *     ),
 *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Put(
 *     path="/services/{id}",
 *     operationId="updateService",
 *     tags={"Services"},
 *     summary="Update a service",
 *     description="Update an existing service. All fields are optional - send only what you want to update.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(ref="#/components/parameters/Id"),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Service data to update",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=255,
 *                 example="Hair Cut Pro"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 nullable=true,
 *                 example="Professional premium haircut"
 *             ),
 *             @OA\Property(
 *                 property="duration",
 *                 type="integer",
 *                 minimum=1,
 *                 maximum=1440,
 *                 example=45
 *             ),
 *             @OA\Property(
 *                 property="price",
 *                 type="number",
 *                 format="float",
 *                 minimum=0,
 *                 example=399.00
 *             ),
 *             @OA\Property(
 *                 property="is_active",
 *                 type="boolean",
 *                 example=true
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Service updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Service updated successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Service")
 *         )
 *     ),
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Delete(
 *     path="/services/{id}",
 *     operationId="deleteService",
 *     tags={"Services"},
 *     summary="Delete a service",
 *     description="Delete (soft delete) a service permanently.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(ref="#/components/parameters/Id"),
 *
 *     @OA\Response(
 *         response=204,
 *         description="Service deleted successfully"
 *     ),
 *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Schema(
 *     schema="Service",
 *     type="object",
 *     title="Service",
 *     description="Service model for booking system",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="Service unique identifier"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         example="Hair Cut",
 *         description="Service name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         example="Professional haircut service",
 *         description="Service description"
 *     ),
 *     @OA\Property(
 *         property="duration",
 *         type="integer",
 *         example=30,
 *         description="Duration in minutes"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         example=299.00,
 *         description="Service price in currency"
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         example=true,
 *         description="Whether service is active"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-15T10:30:00Z",
 *         description="Service creation timestamp"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-15T10:30:00Z",
 *         description="Last update timestamp"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Soft delete timestamp"
 *     )
 * )
 */
class Service {}