<?php

namespace App\Services\FeedService;

use App\Events\FeedChatEvent;
use App\Models\Employee;
use App\Models\FeedChat;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class FeedChatService
{


    public function get($query): \Illuminate\Http\JsonResponse
    {
        $chat = FeedChat::with('employee')->get();
        $result = [];

        if($chat){
            $result = $chat;
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
        $feed_id = $query->feed_id;

        $chatMessage = new FeedChat();
        $chatMessage->message = $message;
        $chatMessage->employee_id = $employee_id;
        $chatMessage->feed_id = $feed_id;
        if ($chatMessage->save()) {
            event(new FeedChatEvent($chatMessage));
            Event::dispatch(new FeedChatEvent($chatMessage));
        }

        return response()->json($chatMessage, Response::HTTP_OK);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $feedChat = FeedChat::find($id);
        $feedChat->delete($feedChat->id);

        return response()->json($feedChat, Response::HTTP_OK);
    }

}
