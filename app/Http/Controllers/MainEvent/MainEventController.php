<?php

namespace App\Http\Controllers\MainEvent;

use App\Http\Controllers\Controller;
use App\Services\MainEvent\MainEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainEventController extends Controller
{

    private MainEventService $service;

    public function __construct(MainEventService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function statuses(): JsonResponse
    {
        return $this->service->statuses();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function allEvents(): JsonResponse
    {
        return $this->service->allEvents();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function allEventsEmployee(Request $request): JsonResponse
    {
        return $this->service->allEventsEmployee($request);
    }

    public function getEventsEmployee(Request $request): JsonResponse
    {
        return $this->service->getEventsEmployee($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        return $this->service->getEvents($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        return $this->service->get($id);
    }

    /**
     * Store a newly created resource in storage.
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
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteEventsEmployee($id): JsonResponse
    {
        return $this->service->deleteEventsEmployee($id);
    }


}
