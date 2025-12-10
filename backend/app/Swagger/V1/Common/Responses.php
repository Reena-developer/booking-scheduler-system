<?php

namespace App\Swagger\V1\Common;

/**
 * Common reusable API responses - Use these with ref="#/components/responses/ResponseName"
 */
class Responses
{
    /**
     * @OA\Response(
     *     response="Success",
     *     description="Request executed successfully",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Operation successful"),
     *         @OA\Property(property="data", type="object")
     *     )
     * )
     */
    public static function Success() {}

    /**
     * @OA\Response(
     *     response="Created",
     *     description="Resource created successfully",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Resource created successfully"),
     *         @OA\Property(property="data", type="object")
     *     )
     * )
     */
    public static function Created() {}

    /**
     * @OA\Response(
     *     response="NoContent",
     *     description="Request successful, no content returned",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=true),
     *         @OA\Property(property="message", type="string", example="Resource deleted successfully")
     *     )
     * )
     */
    public static function NoContent() {}

    /**
     * @OA\Response(
     *     response="ValidationError",
     *     description="Request validation failed",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Validation failed"),
     *         @OA\Property(
     *             property="errors",
     *             type="object",
     *             example={"email": {"The email field is required."}, "password": {"Password must be at least 8 characters."}}
     *         )
     *     )
     * )
     */
    public static function ValidationError() {}

    /**
     * @OA\Response(
     *     response="NotFound",
     *     description="Resource not found",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Resource not found")
     *     )
     * )
     */
    public static function NotFound() {}

    /**
     * @OA\Response(
     *     response="Unauthorized",
     *     description="Authentication required or invalid/expired token",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Unauthorized")
     *     )
     * )
     */
    public static function Unauthorized() {}

    /**
     * @OA\Response(
     *     response="Forbidden",
     *     description="Authenticated but not authorized to perform this action",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Forbidden - insufficient permissions")
     *     )
     * )
     */
    public static function Forbidden() {}

    /**
     * @OA\Response(
     *     response="ServerError",
     *     description="Internal server error",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Internal server error")
     *     )
     * )
     */
    public static function ServerError() {}

    /**
     * @OA\Response(
     *     response="TooManyRequests",
     *     description="Rate limit exceeded",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="Too many requests")
     *     )
     * )
     */
    public static function TooManyRequests() {}
}