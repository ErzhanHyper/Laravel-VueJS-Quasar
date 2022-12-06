<?php

namespace App\Http\Controllers\DocsTemplate;

use App\Http\Controllers\Controller;
use App\Models\DocsTemplate;
use App\Models\DocsTemplateFiles;
use App\Services\DocsService\DocsTemplate\DocsTemplateService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use function config;
use function public_path;
use function request;
use function response;

class DocsTemplateController extends Controller
{


    private DocsTemplateService $service;

    public function __construct(DocsTemplateService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return $this->service->list($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
      return $this->service->get($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function library($id): JsonResponse
    {
      return $this->service->history($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
      return $this->service->store($request);
    }


    public function uploadDoc(Request $request, $id): JsonResponse
    {
      return $this->service->upload($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
      return $this->service->delete($request, $id);
    }
}
