<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use Symfony\Component\HttpFoundation\Response;
use App\Services\ResponseStrategy\StrategyData;
use App\Repositories\GatewayRepositoryInterface;
use App\Services\ResponseStrategy\ResponseContext;

class GatewayController extends Controller
{
    public function __construct(protected GatewayRepositoryInterface $gatewayRepository, protected ResponseContext $responseContext)
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
    {
        return $this->responseContext->executeStrategy(new StrategyData(GatewayResource::collection($this->gatewayRepository->all())));
    }

    public function create()
    {
        // return View('gateway.create');
    }

    public function store(GatewayRequest $request)
    {
        $validatedData = $request->validated();
        $gateway = $this->gatewayRepository->create($validatedData);

        return $this->responseContext->executeStrategy(new StrategyData(new GatewayResource($gateway), 'Gateway created successfully', Response::HTTP_CREATED));
    }

    public function show($id): JsonResponse
    {
        $gateway = $this->gatewayRepository->find($id); //findOrFail
        return $this->responseContext->executeStrategy(new StrategyData(new GatewayResource($gateway)));
    }

    public function edit(Request $request, string $id)
    {
        // TODO
    }

    public function update(GatewayRequest $request, string $id)
    {
        $this->gatewayRepository->find($id); //findOrFail

        $validatedData = $request->validated();
        $updated_data = $this->gatewayRepository->update($id, $validatedData);

        return $this->responseContext->executeStrategy(new StrategyData(new GatewayResource($updated_data), 'Gateway updated successfully', Response::HTTP_OK));
    }

    public function destroy($id): JsonResponse
    {
        $this->gatewayRepository->find($id); //findOrFail
        $this->gatewayRepository->delete($id);

        return $this->responseContext->executeStrategy(new StrategyData([], 'Gateway deleted successfully', Response::HTTP_OK));
    }
}
