<?php

namespace App\OpenApi\Endpoints;

use OpenApi\Annotations as OA;

class GatewayEndpoints
{
    /**
     * @OA\Get(
     *     path="/api/gateway",
     *     tags={"Gateway"},
     *     summary="Gateway index",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/Gateway"))
     *         )
     *    )
     */
    public function index()
    {
        //
    }
    /**
     * @OA\Get(
     *     path="/api/gateway/{id}",
     *     tags={"Gateway"},
     *     summary="Gateway show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Gateway")
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show()
    {
        //.
    }

    /**
     * @OA\Post(
     *     path="/api/gateway",
     *     tags={"Gateway"},
     *     summary="Gateway store",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "serial_number", "IPv4_address"},
     *             @OA\Property(property="serial_number", type="string", example="123456"),
     *             @OA\Property(property="name", type="string", example="Gateway 1"),
     *             @OA\Property(property="IPv4_address", type="string", example="127.0.0.1"),
     *             @OA\Property(
     *                 property="peripheral",
     *                 type="array",
     *                 @OA\Items(type="object", ref="#/components/schemas/Peripheral")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Gateway created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Gateway")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Gateway created successfully",
     *         @OA\Header(
     *             header="Location",
     *             description="/api/gateway",
     *             @OA\Schema(type="string", example="GET /api/gateway")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function store()
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/gateway/{id}",
     *     tags={"Gateway"},
     *     summary="Gateway update",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "serial_number", "IPv4_address"},
     *             @OA\Property(property="serial_number", type="string", example="123456"),
     *             @OA\Property(property="name", type="string", example="Gateway 1"),
     *             @OA\Property(property="IPv4_address", type="string", example="127.0.0.1"),
     *             @OA\Property(
     *                 property="peripheral",
     *                 type="array",
     *                 @OA\Items(type="object", ref="#/components/schemas/Peripheral")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Gateway updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Gateway")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Gateway updated successfully",
     *         @OA\Header(
     *             header="Location",
     *             description="/api/gateway",
     *             @OA\Schema(type="string", example="GET /api/gateway")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update()
    {
        //
    }


    /**
     * @OA\Delete(
     *     path="/api/gateway/{id}",
     *     tags={"Gateway"},
     *     summary="Gateway destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Gateway deleted successfully",
     *         @OA\Header(
     *             header="Location",
     *             description="/api/gateway",
     *             @OA\Schema(type="string", example="GET /api/gateway")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy()
    {
        //
    }
}
