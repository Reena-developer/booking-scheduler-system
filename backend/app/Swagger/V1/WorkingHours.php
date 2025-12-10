<?php

namespace App\Swagger\V1;

/**
 * Working Hours API endpoints - Weekly scheduler (Mon–Sun)
 */

/**
 * @OA\Post(
 *     path="/working-hours/configure-week",
 *     operationId="configureWeeklyWorkingHours",
 *     tags={"Working Hours"},
 *     summary="Configure weekly working hours",
 *     description="Set working hours and breaks for each day of the week (0=Sunday ... 6=Saturday). Each day can be active or day off. Supports multiple breaks per day.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Weekly working hours configuration",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"working_hours"},
 *
 *             @OA\Property(
 *                 property="working_hours",
 *                 type="array",
 *                 description="Weekly configuration for 7 days",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"day_of_week", "is_active"},
 *
 *                     @OA\Property(
 *                         property="day_of_week",
 *                         type="integer",
 *                         minimum=0,
 *                         maximum=6,
 *                         example=1,
 *                         description="0=Sunday, 1=Monday ... 6=Saturday"
 *                     ),
 *                     @OA\Property(
 *                         property="is_active",
 *                         type="boolean",
 *                         example=true,
 *                         description="If false → day is OFF, start_time/end_time can be null"
 *                     ),
 *                     @OA\Property(
 *                         property="start_time",
 *                         type="string",
 *                         nullable=true,
 *                         example="09:00",
 *                         description="Start time in H:i format"
 *                     ),
 *                     @OA\Property(
 *                         property="end_time",
 *                         type="string",
 *                         nullable=true,
 *                         example="17:00",
 *                         description="End time in H:i format"
 *                     ),
 *
 *                     @OA\Property(
 *                         property="breaks",
 *                         type="array",
 *                         description="List of breaks inside working hours",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="start_time", type="string", example="13:00"),
 *                             @OA\Property(property="end_time", type="string", example="14:00")
 *                         )
 *                     )
 *                 )
 *             ),
 *
 *             @OA\Examples(
 *                 example="WeeklyWorkingHoursExample",
 *                 summary="Full week standard working hours",
 *                 value={
 *                     "working_hours"={
 *                         {
 *                             "day_of_week"=0,
 *                             "is_active"=false,
 *                             "start_time"=null,
 *                             "end_time"=null,
 *                             "breaks"={}
 *                         },
 *                         {
 *                             "day_of_week"=1,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         },
 *                         {
 *                             "day_of_week"=2,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         },
 *                         {
 *                             "day_of_week"=3,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         },
 *                         {
 *                             "day_of_week"=4,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         },
 *                         {
 *                             "day_of_week"=5,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         },
 *                         {
 *                             "day_of_week"=6,
 *                             "is_active"=true,
 *                             "start_time"="09:00",
 *                             "end_time"="18:00",
 *                             "breaks"={
 *                                 {"start_time"="13:00", "end_time"="14:00"},
 *                                 {"start_time"="16:30", "end_time"="16:45"}
 *                             }
 *                         }
 *                     }
 *                 }
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Weekly schedule configured successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Weekly schedule configured successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/WorkingHour")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 * @OA\Post(
 *     path="/working-hours/configure-day",
 *     operationId="configureSingleDayWorkingHours",
 *     tags={"Working Hours"},
 *     summary="Configure working hours for a single day",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="day_of_week", type="integer", example=1),
 *             @OA\Property(property="is_active", type="boolean", example=true),
 *             @OA\Property(property="start_time", type="string", nullable=true),
 *             @OA\Property(property="end_time", type="string", nullable=true),
 *             @OA\Property(
 *                 property="breaks",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="start_time", type="string"),
 *                     @OA\Property(property="end_time", type="string"),
 *                     @OA\Property(property="title", type="string"),
 *                     @OA\Property(property="comment", type="string")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *          response=200,
 *         description="Working hours updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Working hours updated successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/WorkingHour")
 *         )
 *      ),
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 * 
 *  @OA\Get(
 *     path="/working-hours",
 *     operationId="getWeeklySchedule",
 *     tags={"Working Hours"},
 *     summary="Get weekly working hours schedule",
 *     description="Retrieve the complete weekly schedule including all working hours and breaks for all 7 days.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Weekly schedule retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Weekly schedule retrieved successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 description="Array of 7 days (Sunday to Saturday)",
 *                 @OA\Items(ref="#/components/schemas/WorkingHour")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401,description="Unauthorized"),
 *     @OA\Response(response=500,description="Server error")
 * )
 * 
 * @OA\Schema(
 *     schema="BreakItem",
 *     type="object",
 *     title="Break Item",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="day_of_week", type="integer", example=1),
 *     @OA\Property(property="start_time", type="string", example="12:30:00"),
 *     @OA\Property(property="end_time", type="string", example="13:00:00")
 * )
 *
 * @OA\Schema(
 *     schema="WorkingHour",
 *     type="object",
 *     title="Working Hour",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="day_of_week", type="integer", example=1),
 *     @OA\Property(property="day_name", type="string", example="Monday"),
 *     @OA\Property(property="start_time", type="string", nullable=true, example="09:00:00"),
 *     @OA\Property(property="end_time", type="string", nullable=true, example="17:00:00"),
 *     @OA\Property(property="is_day_off", type="boolean", example=false),
 *    
 * )
 * 
 * @OA\Get(
 *     path="/working-hours/special-days",
 *     operationId="getSpecialDays",
 *     tags={"Working Hours"},
 *     summary="Fetch all special day rules",
 *     description="Get list of all special day configurations with pagination, sorting, and filters.",
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
 *         description="Special days fetched successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Special days fetched successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="items", type="array",
 *                     @OA\Items(ref="#/components/schemas/SpecialDay")
 *                 ),
 *                 @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 *
 * @OA\Schema(
 *     schema="SpecialDay",
 *     type="object",
 *     title="Special Day",
 *
 *     @OA\Property(property="id", type="integer", example=3),
 *
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         example="2025-12-31"
 *     ),
 *
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Christmas Eve"
 *     ),
 *
 *     @OA\Property(
 *         property="comment",
 *         type="string",
 *         example="Extended break"
 *     ),
 *
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         example=true
 *     ),
 *
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         enum={"full_off", "half_day", "extra_hours"},
 *         example="extra_hours",
 *         description="Special day type"
 *     ),
 *
 *     @OA\Property(
 *         property="half_day_type",
 *         type="string",
 *         nullable=true,
 *         enum={"am", "pm"},
 *         example="am",
 *         description="Used only when type = half_day"
 *     ),
 *
 *     @OA\Property(
 *         property="start_time",
 *         type="string",
 *         nullable=true,
 *         example="13:00",
 *         description="Required only when type = extra_hours"
 *     ),
 *
 *     @OA\Property(
 *         property="end_time",
 *         type="string",
 *         nullable=true,
 *         example="16:00",
 *         description="Required only when type = extra_hours and must be after start_time"
 *     )
 * )
 * 
 *  @OA\Post(
 *     path="/working-hours/special-days",
 *     operationId="createSpecialDay",
 *     tags={"Working Hours"},
 *     summary="Create a special day rule",
 *     description="Add a special day rule such as full off, half day, or extra working hours.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"date", "type"},
 *
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 format="date",
 *                 example="2025-12-31",
 *                 description="Special day date in Y-m-d format"
 *             ),
 *
 *             @OA\Property(
 *                 property="type",
 *                 type="string",
 *                 enum={"full_off", "half_day", "extra_hours"},
 *                 example="extra_hours",
 *                 description="Type of special day"
 *             ),
 *
 *             @OA\Property(
 *                 property="half_day_type",
 *                 type="string",
 *                 nullable=true,
 *                 enum={"am", "pm"},
 *                 example="am",
 *                 description="Required only when type = half_day"
 *             ),
 *
 *             @OA\Property(
 *                 property="start_time",
 *                 type="string",
 *                 nullable=true,
 *                 example="13:00",
 *                 description="Required only when type = extra_hours"
 *             ),
 *
 *             @OA\Property(
 *                 property="end_time",
 *                 type="string",
 *                 nullable=true,
 *                 example="16:00",
 *                 description="Required only when type = extra_hours and must be after start_time"
 *             ),
 *
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 nullable=true,
 *                 maxLength=255,
 *                 example="New Year Eve",
 *                 description="Optional title for special day"
 *             ),
 *
 *             @OA\Property(
 *                 property="comment",
 *                 type="string",
 *                 nullable=true,
 *                 example="Office closes early",
 *                 description="Optional comment"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Special day created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Special day created successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/SpecialDay")
 *         )
 *     ),
 *
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 * 
 *  * @OA\Delete(
 *     path="/working-hours/special-days/{id}",
 *     operationId="deleteSpecialDay",
 *     tags={"Working Hours"},
 *     summary="Delete a special day rule",
 *     description="Deletes a special day by ID.",
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the special day to delete",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Special day deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Special day deleted successfully")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Special day not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Special day not found")
 *         )
 *     ),
 *
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 */




class WorkingHours {}
