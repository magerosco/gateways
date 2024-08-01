<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeripheralRequest;
use App\Models\Peripheral;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\PeripheralResource;

class PeripheralController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PeripheralResource::collection(Peripheral::all());
    }

    public function store(PeripheralRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $peripheral = Peripheral::create($validatedData);

        return response()->json(
            [
                'data' => new PeripheralResource($peripheral),
                'message' => 'Gateway created successfully',
            ],
            Response::HTTP_CREATED,
        );
    }

    public function show($id): JsonResponse
    {
        $peripheral = Peripheral::find($id);
        if (!$peripheral) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return response()->json(
            [
                'data' => new PeripheralResource($peripheral),
            ],
            Response::HTTP_OK,
        );
    }
    public function update(PeripheralRequest $request, String $id): JsonResponse
    {
        $peripheral = Peripheral::find($id);
        if (!$peripheral) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validated();
        $peripheral->update($validatedData);
        return response()->json(
            [
                'data' => new PeripheralResource($peripheral),
                'message' => 'Peripheral updated successfully',
            ],
            Response::HTTP_OK,
        );
    }
    public function destroy($id): JsonResponse
    {
        $peripheral = Peripheral::find($id);
        if (!$peripheral) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $peripheral->delete();
        return response()->json(
            [
                'message' => 'Peripheral deleted successfully',
            ],
            Response::HTTP_OK,
        );
    }
}
