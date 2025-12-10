<?php

namespace App\Swagger\V1\Common;

/**
 * Reusable Schema definitions - Use these with ref="#/components/schemas/SchemaName"
 */
class Schemas
{
    /**
     * @OA\Schema(
     *     schema="Pagination",
     *     type="object",
     *     title="Pagination Meta",
     *     description="Pagination information for list responses",
     *     @OA\Property(property="current_page", type="integer", example=1, description="Current page number"),
     *     @OA\Property(property="per_page", type="integer", example=15, description="Items per page"),
     *     @OA\Property(property="total", type="integer", example=156, description="Total number of items"),
     *     @OA\Property(property="last_page", type="integer", example=11, description="Last page number"),
     *     @OA\Property(property="from", type="integer", example=1, description="First item index on page"),
     *     @OA\Property(property="to", type="integer", example=15, description="Last item index on page")
     * )
     */
    public static function Pagination() {}

    /**
     * @OA\Schema(
     *     schema="PaginatedResponse",
     *     type="object",
     *     title="Paginated List Response",
     *     description="Standard paginated response structure",
     *     @OA\Property(property="status", type="boolean", example=true),
     *     @OA\Property(property="message", type="string", example="Items fetched successfully"),
     *     @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(
     *             property="items",
     *             type="array",
     *             @OA\Items(type="object")
     *         ),
     *         @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *     )
     * )
     */
    public static function PaginatedResponse() {}

    /**
     * @OA\Schema(
     *     schema="TokenResponse",
     *     type="object",
     *     title="Authentication Token Response",
     *     @OA\Property(property="access_token", type="string", example="1|AiVYK8YFOdtV4rnmccRCeRo32RQ8FTtOb7WFvz1t7a0fb34e"),
     *     @OA\Property(property="token_type", type="string", example="Bearer"),
     *     @OA\Property(property="expires_in", type="integer", example=3600, description="Token expiration in seconds")
     * )
     */
    public static function TokenResponse() {}

    /**
     * @OA\Schema(
     *     schema="Timestamp",
     *     type="object",
     *     title="Timestamp Fields",
     *     description="Standard created/updated timestamps",
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:30:00Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-15T10:30:00Z"),
     *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     * )
     */
    public static function Timestamp() {}

    /**
     * @OA\Schema(
     *     schema="Error",
     *     type="object",
     *     title="Error Response",
     *     @OA\Property(property="status", type="boolean", example=false),
     *     @OA\Property(property="message", type="string"),
     *     @OA\Property(property="errors", type="object", nullable=true)
     * )
     */
    public static function Error() {}
}