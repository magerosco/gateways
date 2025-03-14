<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Requests\PeripheralRequest;
use App\Http\Resources\PeripheralResource;
use App\Repositories\CrudRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Anasa\ResponseStrategy\ResponseContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Anasa\ResponseStrategy\OutputDataFormat\StrategyDataInterface;

class PeripheralController extends Controller
{
    public function __construct(
        protected CrudRepositoryInterface $repository,
        protected ResponseContextInterface $responseContext,
        protected StrategyDataInterface $strategyData
    ) {
        $this->repository = $repository;
        $this->responseContext = $responseContext;
        $this->strategyData = $strategyData;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        $data = $this->repository->all();
        $strategy = $this->strategyData->setStrategyData(PeripheralResource::collection($data));
        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * @todo The view is not implemented
     */
    public function create(): View
    {
        return View('peripheral.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeripheralRequest $request): JsonResponse|RedirectResponse
    {
        $validatedData = $request->validated();
        $data = $this->repository->create($validatedData);

        $strategy = $this->strategyData->setStrategyData(
            new PeripheralResource($data),
            'Peripheral created successfully',
            Response::HTTP_CREATED
        );

        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * @todo The view is not implemented
     */
    public function show($id): JsonResponse|View
    {
        $data = $this->repository->find($id); //it uses findOrFail
        $strategy = $this->strategyData->setStrategyData(new PeripheralResource($data));

        return $this->responseContext->executeStrategy($strategy);
    }

    /**
     * @todo The view is not implemented
     */
    public function edit(string $id): View
    {
        $data = $this->repository->find($id); //it uses findOrFail
        return View('peripheral.edit', ['peripheral' => $data]);
    }

    public function update(PeripheralRequest $request, string $id): JsonResponse|RedirectResponse
    {
        //This is not a validation, it only returns the validated data after be validated.
        $validatedData = $request->validated();
        $updated_data = $this->repository->update($id, $validatedData); //it uses findOrFail

        $strategy = $this->strategyData->setStrategyData(
            new PeripheralResource($updated_data),
            'Peripheral updated successfully',
            Response::HTTP_OK
        );

        return $this->responseContext->executeStrategy($strategy);
    }

    public function destroy($peripheral): JsonResponse|RedirectResponse
    {
        $this->repository->delete($peripheral);

        return $this->responseContext->executeStrategy(
            $this->strategyData->setStrategyData(
                [],
                'Peripheral deleted successfully',
                Response::HTTP_OK
            )
        );
    }
}
