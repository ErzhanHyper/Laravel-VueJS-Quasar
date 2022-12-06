<?php

namespace App\Services\PollingService;

use App\Models\Employee;
use App\Models\PollingComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PollingCommentService
{

    public function storeComment(Request $request): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $comment = new PollingComment();
        $comment->employee_id = $employee_id;
        $comment->polling_id = $request->polling_id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json($comment, Response::HTTP_OK);
    }

    public function comments(): JsonResponse
    {
        $polling = PollingComment::with('employee')->orderByDesc('created_at')->get();
        return response()->json($polling);
    }

}
