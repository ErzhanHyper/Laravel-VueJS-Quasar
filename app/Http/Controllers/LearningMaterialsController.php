<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Services\LearningMaterialsService\LearningMaterialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LearningMaterialsController extends Controller
{
    private LearningMaterialService $service;

    public function __construct(LearningMaterialService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function catalog(Request $request): JsonResponse
    {
        return $this->service->catalog();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function catalogDetail($id): JsonResponse
    {
        return $this->service->catalogDetail($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function catalogDelete($id): JsonResponse
    {
        return $this->service->catalogDelete($id);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->service->getList();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeCatalog(Request $request): JsonResponse
    {
        return $this->service->storeCatalog($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeViewer(Request $request): JsonResponse
    {
        return $this->service->storeViewer($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LearningMaterial  $learningMaterial
     * @return Response
     */
    public function show($id)
    {
        return $this->service->getDetail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\LearningMaterial  $learningMaterial
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LearningMaterial  $learningMaterial
     * @return Response
     */
    public function destroy($id)
    {
        return $this->service->delete($id);
    }

}
