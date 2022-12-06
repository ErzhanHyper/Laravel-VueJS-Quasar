<?php

namespace App\Http\Controllers\DocsRegulation;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocsRegulationFileResource;
use App\Models\DocsRegulation;
use App\Models\DocsRegulationFiles;
use App\Services\DocsService\DocsRegulation\DocsRegulationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;
use function config;
use function response;

class DocsRegulationController extends Controller
{

    private DocsRegulationService $service;

    public function __construct(DocsRegulationService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function list(Request $request): ResourceCollection
    {
        return $this->service->list($request);
    }

    /**
     * Get the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function get($id)
    {
        return $this->service->get($id);
    }

    /**
     * Get Image resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function getDocsImage($id): JsonResponse
    {
        return $this->service->docsImage($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function library($id)
    {
        return $this->service->library($id);
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return bool|JsonResponse
     */
    public function updateDocs(Request $request, $id): bool|JsonResponse
    {
        return $this->service->updateDocs($request, $id);
    }

    /**
     * Update resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return bool|JsonResponse
     */
    public function update(Request $request, $id): bool|JsonResponse
    {
        return $this->service->update($request, $id);
    }

    /**
     * Get the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function storeViewer(Request $request): JsonResponse
    {
        return $this->service->storeViewer($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

}
