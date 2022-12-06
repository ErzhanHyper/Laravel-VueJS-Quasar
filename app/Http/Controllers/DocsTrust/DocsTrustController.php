<?php

namespace App\Http\Controllers\DocsTrust;

use App\Http\Controllers\Controller;
use App\Services\DocsService\DocsTrust\DocsTrustService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocsTrustController extends Controller
{

    private DocsTrustService $service;

    public function __construct(DocsTrustService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function list(Request $request): ResourceCollection
    {
        return $this->service->list($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        return $this->service->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function history($id): JsonResponse
    {
        return $this->service->history($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return $this->service->store($request);
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
    public function destroy($id): JsonResponse
    {
        return $this->service->delete($id);
    }
}
