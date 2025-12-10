<?php

namespace App\Swagger\V1;

/**
 * @OA\Post(
 *     path="/login",
 *     tags={"Auth"},
 *     summary="Login provider",
 *     description="Authenticate user and return access token",
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="admin@gmail.com"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="Smart$Booking1"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login successful"),
 *
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     example="2|AiVYK8YFOdtV4rnmccRCeRo32RQ8FTtOb7WFvz1t7a0fb34e"
 *                 ),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Admin Provider"),
 *                     @OA\Property(property="email", type="string", example="admin@gmail.com")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(response=422, ref="#/components/responses/ValidationError"),
 *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
 *     @OA\Response(response=500, ref="#/components/responses/ServerError")
 * )
 */
class Auth {}
