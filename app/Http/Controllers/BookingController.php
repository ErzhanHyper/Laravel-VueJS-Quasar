<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function storeConference(Request $request): \Illuminate\Http\JsonResponse
    {


        $validator = Validator::make($request->all(),
            [
                'date' => 'required',
                'start' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }


        $time_start = Carbon::parse($request->date . ' ' . $request->start)->format('Y-m-d H:i');
        $time_end = Carbon::parse($request->date . ' ' . $request->end)->subMinute()->format('Y-m-d H:i');
        $date = strtotime(Carbon::parse($request->date)->format('Y-m-d'));
        $findDate = Booking::where('date', $date)->where('room', $request->room)->get();

        $collect = collect();
        foreach ($findDate as $item) {
            if (
                in_array($item->time_start, range(strtotime($time_start), strtotime($time_end)), true)
                ||
                in_array(Carbon::parse($item->time_end)->subMinute()->timestamp, range(strtotime($time_start), strtotime($time_end)), true)
                ||
                in_array(strtotime($time_start), range($item->time_start, Carbon::parse($item->time_end)->subMinute()->timestamp), true)
                ||
                in_array(strtotime($time_end), range($item->time_start, Carbon::parse($item->time_end)->subMinute()->timestamp), true)

            ) {
                $collect->push($item);
            }

        }

        $sorted = $collect->sortByDesc(function ($card) {
            return $card['time_start'];
        });

        if (count($sorted) > 0) {
            $result = [['Конференц-зал уже забронирован']];
            return response()->json($result, Response::HTTP_EXPECTATION_FAILED);
        }

        $time_end = NULL;
        if ($request->end) {
            $time_end = Carbon::parse($request->date)->format('Y-m-d') . ' ' . Carbon::parse($request->end)->format('H:i');
        }
        $employee_id = Employee::where('user_id', auth()->user()->id)->first()->id;
        $booking = new Booking();
        $booking->time_start = strtotime(Carbon::parse($request->date)->format('Y-m-d') . ' ' . Carbon::parse($request->start)->format('H:i'));
        $booking->time_end = strtotime($time_end);
        $booking->date = strtotime(Carbon::parse($request->date)->format('Y-m-d'));
        $booking->employee_id = $employee_id;
        $booking->department_id = $request->department_id;
        $booking->type = 'conference';
        $booking->room = $request->room;
        $booking->save();


        return response()->json($booking);
    }

    public function getTodayConference(): \Illuminate\Http\JsonResponse
    {
        $today = Carbon::today()->timestamp;
        $now = Carbon::now()->addHours(6)->format('Y-m-d H:i');
        $booking = Booking::with('employee')
            ->where('time_end', '>', strtotime($now))
            ->where('type', 'conference')->where('date', '>=', $today)->orderBy('date')->orderBy('time_start')->get();

        return response()->json(BookingResource::collection($booking), Response::HTTP_OK);
    }


    public function getLastTodayConference(): \Illuminate\Http\JsonResponse
    {
        $today = Carbon::now()->addHours(6)->format('Y-m-d');
        $now = Carbon::now()->addHours(6)->format('Y-m-d H:i');
        $booking = Booking::with(['employee', 'department'])->where('type', 'conference')->where('date', strtotime($today))
            ->where('time_end', '>=', strtotime($now))
            ->orderBy('time_start')
            ->first();

        $result = [];

        if($booking){
            $result = new BookingResource($booking);
        }

        return response()->json($result, Response::HTTP_OK);
    }

    public function deleteConference($id)
    {
        $booking = Booking::find($id);
        $booking->delete();

        $result = ['message' => 'deleted successfully!'];

        return response()->json($result, Response::HTTP_OK);
    }
}
