<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GatewayResource;
use Illuminate\Http\Request;
use App\Models\Gateway;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\GatewayRequest;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return GatewayResource::collection(Gateway::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GatewayRequest $request)
    {
        $validatedData = $request->validated();

        $gataway = Gateway::create($validatedData);

        return response()->json(
            [
                'data' => new GatewayResource($gataway),
                'message' => 'Gateway created successfully',
            ],
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $gateway = Gateway::find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        return response()->json(
            [
                'data' => new GatewayResource($gateway),
            ],
            Response::HTTP_OK,
        );
    }

    public function update(GatewayRequest $request, String $id)
    {
        $gateway = Gateway::find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $validatedData = $request->validated();
        $gateway->update($validatedData);
        return response()->json(
            [
                'data' => new GatewayResource($gateway),
                'message' => 'Gateway updated successfully',
            ],
            Response::HTTP_OK,
        );
    }

    public function destroy($id): JsonResponse
    {
        $gateway = Gateway::find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $gateway->delete();
        return response()->json(
            [
                'message' => 'Gateway deleted successfully',
            ],
            Response::HTTP_OK,
        );
    }
}
