<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\ProjectChat;
use App\Services\ProjectService\ProjectChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectChatController extends Controller
{
    private ProjectChatService $service;

    public function __construct(ProjectChatService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request): JsonResponse
    {
        return $this->service->get($request);
    }

    public function files(Request $request): JsonResponse
    {
        return $this->service->files($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->service->send($request);
    }

    public function delete($id): JsonResponse
    {
        return $this->service->delete($id);
    }
}
