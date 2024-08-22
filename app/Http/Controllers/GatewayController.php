<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\GatewayRepositoryInterface;
use App\Services\ResponseStrategy\ResponseContextInterface;

class GatewayController extends Controller
{
    public function __construct(protected GatewayRepositoryInterface $gatewayRepository, protected ResponseContextInterface $responseContext)
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
    {
        return $this->responseContext->executeStrategy(GatewayResource::collection($this->gatewayRepository->all()));
    }

    public function create()
    {
        // return View('gateway.create');
    }

    public function store(GatewayRequest $request)
    {
        $validatedData = $request->validated();

        $gateway = $this->gatewayRepository->create($validatedData);

        return $this->responseContext->executeStrategy(new GatewayResource($gateway), 'Gateway created successfully', Response::HTTP_CREATED);
    }

    public function show($id): JsonResponse
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        return $this->responseContext->executeStrategy(new GatewayResource($gateway));
    }

    public function edit(Request $request, string $id)
    {
        // TODO
    }

    public function update(GatewayRequest $request, string $id)
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $validatedData = $request->validated();
        $this->gatewayRepository->update($id, $validatedData);

        return $this->responseContext->executeStrategy(new GatewayResource($gateway), 'Gateway updated successfully', Response::HTTP_OK);
    }

    public function destroy($id): JsonResponse
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $this->gatewayRepository->delete($id);

        return $this->responseContext->executeStrategy([], 'Gateway deleted successfully', Response::HTTP_OK);
    }
}
