<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Gateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use App\Repositories\CrudRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Gateway\GatewayServiceInterface;

class GatewayController extends Controller
{
    public function __construct(
        protected CrudRepositoryInterface $repository,
        protected GatewayServiceInterface $gatewayService // EXAMPLE: it's part of service layer design pattern example
    ) {}

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

    public function destroy(Gateway $gateway): JsonResponse
    {
        try {
            /**
             * EXAMPLE:  Use the service layer pattern when you
             * want to keep the logic out of the controller
             */
            $this->gatewayService->destroyV2($gateway);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(
                [
                    'message' => 'Bad request.',
                    'origin' => 'From V2',
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }
        return response()->json(
            [
                'message' => 'Gateway deleted successfully',
                'origin' => 'From V2',
            ],
            Response::HTTP_OK,
        );
    }
}
