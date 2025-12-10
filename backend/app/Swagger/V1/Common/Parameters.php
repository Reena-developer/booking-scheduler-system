<?php

namespace App\Swagger\V1\Common;

/**
 * Common reusable query parameters for pagination, sorting, filtering, etc.
 */
class Parameters
{
    /**
     * @OA\Parameter(
     *     parameter="Page",
     *     name="page",
     *     in="query",
     *     required=false,
     *     description="Page number (1-indexed)",
     *     @OA\Schema(type="integer", example=1, minimum=1)
     * )
     */
    public static function Page() {}

    /**
     * @OA\Parameter(
     *     parameter="PerPage",
     *     name="per_page",
     *     in="query",
     *     required=false,
     *     description="Number of items per page (max 100)",
     *     @OA\Schema(type="integer", example=15, minimum=1, maximum=100)
     * )
     */
    public static function PerPage() {}

    /**
     * @OA\Parameter(
     *     parameter="SortBy",
     *     name="sort_by",
     *     in="query",
     *     required=false,
     *     description="Field name to sort by",
     *     @OA\Schema(type="string", example="created_at")
     * )
     */
    public static function SortBy() {}

    /**
     * @OA\Parameter(
     *     parameter="SortOrder",
     *     name="sort_order",
     *     in="query",
     *     required=false,
     *     description="Sort direction",
     *     @OA\Schema(type="string", enum={"asc", "desc"}, example="desc")
     * )
     */
    public static function SortOrder() {}

    /**
     * @OA\Parameter(
     *     parameter="Search",
     *     name="search",
     *     in="query",
     *     required=false,
     *     description="Search keyword",
     *     @OA\Schema(type="string", example="hair cut")
     * )
     */
    public static function Search() {}

    /**
     * @OA\Parameter(
     *     parameter="Status",
     *     name="status",
     *     in="query",
     *     required=false,
     *     description="Filter by status",
     *     @OA\Schema(type="boolean")
     * )
     */
    public static function Status() {}

    /**
     * @OA\Parameter(
     *     parameter="Id",
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Resource ID",
     *     @OA\Schema(type="integer", minimum=1)
     * )
     */
    public static function Id() {}
}