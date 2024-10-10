<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation")
 *
 * @OA\Tag(name="Gateway", description="Gateway crud")
 * @OA\Schema(
 *       schema="Gateway",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="serial_number", type="string", example="1234567"),
 *     @OA\Property(property="name", type="string", example="Gateway 1"),
 *     @OA\Property(property="IPv4_address", type="string", example="127.0.0.1"),
 *     @OA\Property(
 *         property="peripheral",
 *         type="array",
 *         @OA\Items(type="object", ref="#/components/schemas/Peripheral")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z")
 * )
 */
class GatewayResourceSchema
{
}
