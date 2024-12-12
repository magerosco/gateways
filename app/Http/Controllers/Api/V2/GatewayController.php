<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use App\Repositories\GatewayRepository;
use Symfony\Component\HttpFoundation\Response;

class GatewayController extends Controller
{
    public function __construct(protected GatewayRepository $repository)
    {
    }

    public function index(): JsonResponse
    {
        $gateway = $this->repository->all();

        return response()->json(['data' => GatewayResource::collection($gateway), 'origin' => 'From V2']);
    }

    public function show($id): JsonResponse
    {
        $gateway = $this->repository->find($id); //it uses findOrFail
        return response()->json([
            'data' => new GatewayResource($gateway),
            'origin' => 'From V2',
        ]);
    }

    public function store(GatewayRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $gateway = $this->repository->create($validatedData);
        return response()->json(
            [
                'data' => new GatewayResource($gateway),
                'message' => 'Gateway created successfully',
                'origin' => 'From V2',
            ],
            Response::HTTP_CREATED,
        );
    }

    public function update(GatewayRequest $request, string $id): JsonResponse
    {
        $validatedData = $request->validated();
        /**
         * It uses [updateGateway] instead of update because it is implemented to test the GatewayRepositoryDecorator
         * to test even trigger for specific function.
         */
        $updated_data = $this->repository->updateGateway($id, $validatedData); //it uses findOrFail
        return response()->json(
            [
                'data' => new GatewayResource($updated_data),
                'message' => 'Gateway updated successfully',
                'origin' => 'From V2',
            ],
            Response::HTTP_OK,
        );
    }

    public function destroy($gateway): JsonResponse
    {

        /******************************************************
         * This security control is just for run the tests,
         * it must be in a middleware, just like the Version 1
         **************************************************** */
        if (!str_contains($gateway->name, 'policy') && request()->user()->email != 'admin@admin.com') {
            return response()->json(
                [
                    'message' => 'You are not authorized.',
                    'origin' => 'From V2',
                ],
                Response::HTTP_FORBIDDEN,
            );
        }
        /*********************** */


        $this->repository->delete($gateway);

        return response()->json(
            [
                'message' => 'Gateway deleted successfully',
                'origin' => 'From V2',
            ],
            Response::HTTP_OK,
        );
    }
}
