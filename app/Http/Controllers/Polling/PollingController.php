<?php

namespace App\Http\Controllers\Polling;

use App\Http\Controllers\Controller;
use App\Services\PollingService\PollingCommentService;
use App\Services\PollingService\PollingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PollingController extends Controller
{

    private PollingService $service;
    private PollingCommentService $comment_service;


    public function __construct(PollingService $service, PollingCommentService $comment_service)
    {
        $this->service = $service;
        $this->comment_service = $comment_service;
    }


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return JsonResponse
     */

    public function choiceCount($id): JsonResponse
    {
        return $this->service->choiceCount($id);
    }

    public function list(): JsonResponse
    {
        return $this->service->list();
    }

    public function get($id): JsonResponse
    {
        return $this->service->get($id);
    }

    public function last(): JsonResponse
    {
        return $this->service->last();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->service->store($request);
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->service->update($request, $id);
    }

    public function destroy($id): JsonResponse
    {
        return $this->service->destroy($id);
    }

    public function storeComment(Request $request): JsonResponse
    {
        return $this->comment_service->storeComment($request);
    }

    public function comments(): JsonResponse
    {
        return $this->comment_service->comments();
    }

}
