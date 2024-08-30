<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use App\Repositories\CrudRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Services\ResponseStrategy\ResponseContextInterface;
use App\Services\ResponseStrategy\OutputDataFormat\StrategyData;
use App\Services\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

class GatewayController extends Controller
{
    public function __construct(protected CrudRepositoryInterface $repository, protected ResponseContextInterface $responseContext, protected StrategyDataInterface $strategyData)
    {
    }

    /**
     * @todo The view is not implemented
     */
    public function index(): View|JsonResponse
    {
        $gateway = $this->repository->all();
        $strategy = $this->strategyData->setStrategyData(GatewayResource::collection($gateway));
        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * @todo The view is not implemented
     */
    public function create(): View
    {
        return View('gateway.create');
    }

    public function store(GatewayRequest $request): JsonResponse|RedirectResponse
    {
        $validatedData = $request->validated(); //  this is not a validation, it only returns the validated data after be validated from the GatewayRequest
        $gateway = $this->repository->create($validatedData);

        $strategy = $this->strategyData->setStrategyData(new GatewayResource($gateway), 'Gateway created successfully', Response::HTTP_CREATED);

        return $this->responseContext->executeStrategy($strategy);
    }
    /**
     * @todo The view is not implemented
     */

    public function show($id): JsonResponse|View
    {
        $gateway = $this->repository->find($id); //it uses findOrFail
        $strategy = $this->strategyData->setStrategyData(new GatewayResource($gateway));

        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * @todo The view is not implemented
     */
    public function edit(string $id): View
    {
        $gateway = $this->repository->find($id); //it uses findOrFail
        return View('gateway.edit', ['gateway' => $gateway]);
    }

    public function update(GatewayRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $validatedData = $request->validated();//  this is not a validation, it only returns the validated data after be validated from the GatewayRequest
        $updated_data = $this->repository->update($id, $validatedData); //it uses findOrFail

        $strategy = $this->strategyData->setStrategyData(new GatewayResource($updated_data), 'Gateway updated successfully', Response::HTTP_OK);

        return $this->responseContext->executeStrategy($strategy);
    }

    public function destroy($id): JsonResponse|RedirectResponse
    {
        $this->repository->delete($id); //it uses findOrFail

        return $this->responseContext->executeStrategy($this->strategyData->setStrategyData([], 'Gateway deleted successfully', Response::HTTP_OK));
    }
}
