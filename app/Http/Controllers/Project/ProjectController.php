<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectService\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    private ProjectService $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request, $id): JsonResponse
    {
        return $this->service->get($request, $id);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->update($request, $id);
    }

    public function list(Request $request): JsonResponse
    {
        return $this->service->list($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->service->store($request);
    }

    public function delete($id): JsonResponse
    {
        return $this->service->delete($id);
    }

    public function storeEmployee(Request $request): JsonResponse
    {
        $project_id = $request->project_id;
        $employee_ids = $request->employees;

        return $this->service->storeProjectEmployee($project_id, $employee_ids);
    }

    public function removeEmployee(Request $request): JsonResponse
    {
        return $this->service->removeProjectEmployee($request);
    }

    public function inviteEmployee(Request $request): JsonResponse
    {
        return $this->service->inviteProjectEmployee($request);
    }



}
