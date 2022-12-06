<?php

namespace App\Http\Controllers\DocsContract;

use App\Http\Controllers\Controller;
use App\Models\DocsContract;
use App\Services\DocsService\DocsContract\DocsContractService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocsContractController extends Controller
{

    private DocsContractService $service;

    public function __construct(DocsContractService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        return $this->service->list($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storePayment(Request $request): JsonResponse
    {
        return $this->service->storePayment($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAdditional($id): JsonResponse
    {
        return $this->service->getAdditional($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPayment($id): JsonResponse
    {
        return $this->service->getPayment($id);
    }

    /**
     * Display the specified resource.
     *
     * @param DocsContract $docsContract
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        return $this->service->get($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->update($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function deletePayment($id): JsonResponse
    {
        return $this->service->deletePayment($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteAdditional($id): JsonResponse
    {
        return $this->service->deleteAdditional($id);
    }




}
