<?php

namespace App\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Peripheral", description="Peripheral crud")
 *
 * @OA\Schema(
 *     schema="Peripheral",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="UID", type="string", example="1234567"),
 *     @OA\Property(property="vendor", type="string", example="vendor TEST CREATED"),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"online", "offline"},
 *         example="offline"
 *     ),
 *     @OA\Property(property="gateway_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T00:00:00.000000Z")
 * )
 */
class PeripheralResourceSchema
{
}
