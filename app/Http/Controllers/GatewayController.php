<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\GatewayRepositoryInterface;
use App\Services\ResponseStrategy\ResponseContext;

class GatewayController extends Controller
{
    public function __construct(protected GatewayRepositoryInterface $gatewayRepository, protected ResponseContext $responseContext)
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|JsonResponse
    {
        $gateways = $this->gatewayRepository->all();
        return $this->responseContext->executeStrategy($gateways);
    }

    public function create()
    {
        // return View('gateway.create');
    }

    public function store(GatewayRequest $request)
    {
        $validatedData = $request->validated();

        $gataway = $this->gatewayRepository->create($validatedData);
        $data = new GatewayResource($gataway);

        if ($request->get('is_api')) {
            return response()->json(['data' => $data, 'message' => 'Gateway created successfully'], Response::HTTP_CREATED);
        } else {
            return redirect()
                ->route('gateway.index')
                ->with(['data' => $data->toJson(), 'message' => 'Gateway created successfully'], Response::HTTP_CREATED);
        }
    }

    public function show(Request $request, $id): JsonResponse
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        if ($request->get('is_api')) {
            return response()->json(['data' => new GatewayResource($gateway)], Response::HTTP_OK);
        } else {
            return view('gateway.show', ['gateway' => $gateway->toJson()]);
        }
    }

    public function edit(Request $request, string $id)
    {
        if ($request->get('is_api')) {
            return response()->json(['data' => ['id' => $id]], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(GatewayRequest $request, string $id)
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $validatedData = $request->validated();
        $this->gatewayRepository->update($id, $validatedData);

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
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        $this->gatewayRepository->delete($id);
        return response()->json(
            [
                'message' => 'Gateway deleted successfully',
            ],
            Response::HTTP_OK,
        );
    }
}
