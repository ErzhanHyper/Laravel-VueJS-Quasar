<?php

namespace App\Services\ProjectService;

use App\Models\Employee;
use App\Models\ProjectChat;
use App\Models\ProjectEmployee;
use App\Services\File\FileStore;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class ProjectChatService
{

    public FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function get($query): \Illuminate\Http\JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        if ($employee_id !== 1) {
            $project_employee = ProjectEmployee::where('employee_id', $employee_id)->where('project_id', $query->project_id)->first();
            if ($project_employee) {
                $chat = ProjectChat::with('employee')->where('project_id', $query->project_id)->orderByDesc('updated_at')->get();
            }
        }else{
            $chat = ProjectChat::with('employee')->where('project_id', $query->project_id)->orderByDesc('updated_at')->get();
        }
        $result = [];

        if ($chat) {
            $result = $chat;
        }
        return response()->json($result);
    }

    public function files($query): \Illuminate\Http\JsonResponse
    {
        $chat = ProjectChat::where('project_id', $query->project_id)->orderByDesc('created_at')->get();
        $result = [];

        if ($chat) {
            foreach ($chat as $ch) {
                if ($ch->files != null) {
                    foreach (json_decode($ch->files) as $file) {
                        $result[] = [
                            'file' => config('app.url') . '/storage/uploads/' . $file->path,
                            'name' => $file->name,
                            'date' => $ch->created_at
                        ];
                    }
                }
            }
        }
        return response()->json($result);
    }

    public function send($query): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'message' => 'required',
            ],
        );

        if ($validator->fails()) {
            response()->json($validator->messages(), Response::HTTP_EXPECTATION_FAILED);
        }

        $message = $query->message;
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $project_id = $query->project_id;
        $parent_id = $query->parent_id;
        $chatMessage = new ProjectChat();
        $chatMessage->message = $message;
        $chatMessage->employee_id = $employee_id;
        $chatMessage->project_id = $project_id;
        $chatMessage->parent_id = $parent_id;

        if ($parent_id !== 0) {
            $parentChat = ProjectChat::find($parent_id);
            if ($parentChat) {
                $parentChat->updated_at = Carbon::now();
                $parentChat->save();
            }
        }
        if ($query->file('files') && count($query->file('files')) > 0) {
            $files = [];
            foreach ($query->file('files') as $file) {
                $path = $this->file_store_service->store($file, 'project/chat');
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path
                ];
            }
            $chatMessage->files = json_encode($files);
        }

        $chatMessage->save();

        return response()->json($chatMessage, Response::HTTP_OK);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $projectChat = ProjectChat::find($id);
        $projectChat->delete($projectChat->id);

        return response()->json($projectChat, Response::HTTP_OK);
    }
}
