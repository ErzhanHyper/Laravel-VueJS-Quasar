<?php

namespace App\Services\MainEvent;

use App\Http\Controllers\MailController;
use App\Http\Resources\MainEventFileResource;
use App\Models\Employee;
use App\Models\MainEvent;
use App\Models\MainEventEmployee;
use App\Models\MainEventFile;
use App\Models\MainEventStatus;
use App\Services\File\FileStore;
use Carbon\Carbon;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MainEventService
{
    public FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function statuses(): JsonResponse
    {
        $status = MainEventStatus::all();
        return response()->json($status);
    }

    public function allEvents(): JsonResponse
    {
        $main_events = MainEvent::with(['employees'])->get();
        $events = [];
        foreach ($main_events as $item) {
            $events[] = [
                'date' => Carbon::parse($item->datetime)->format('Y-m-d') .' '. Carbon::parse($item->time)->format('H:i'),
                'title' => $item->title,
                'employees' => $item->employees
            ];
        }
        return response()->json($events);
    }

    public function getEvents($query): JsonResponse
    {
        $employee_id = $query->id;
        $main_events = MainEvent::with(['employees'])->get();
        $events = [];

        foreach ($main_events as $item) {
            $employeess = [];
            $employee_exist = false;
            $employees = [];
            foreach ($item->employees as $employee) {
                $employees[] = Employee::find($employee->employee_id)->id;
            }

            if (in_array($employee_id, $employees)) {
                $employee_exist = true;
            }

            if ($employee_exist === true) {
                $events[] = [
                    'date' => Carbon::parse($item->datetime)->format('Y-m-d') .' '. Carbon::parse($item->time)->format('H:i'),
                    'title' => $item->title,
                ];
            }
        }
        return response()->json($events);
    }

    public function getEventsEmployee($query): JsonResponse
    {
        $employee_id = $query->id;
        $date = strtotime(Carbon::parse($query->date)->format('Y-m-d'));
        $main_events = MainEvent::with(['employees'])->where('datetime', $date)->where('title', $query->title)->get();
        $events = [];
        $files = MainEventFile::all();

        foreach ($main_events as $item) {
            $employees = [];
            $main_event_files = [];
            $employee_exist = false;
            $employeess = [];

            foreach ($item->employees as $employee) {
                $employees[] = Employee::find($employee->employee_id)->id;
            }

            if (in_array($employee_id, $employees)) {
                $employee_exist = true;
            }

            if ($employee_exist === true) {
                foreach ($files as $file) {
                    foreach (json_decode($file->event_ids) as $id) {
                        if ($id === $item->id) {
                            $main_event_files[] = $file;
                        }
                    }
                }
                foreach ($item->employees as $employee) {
                    $employeess[] = Employee::find($employee->employee_id);
                }

                if (count($main_event_files) > 0) {
                    $main_event_files = MainEventFileResource::collection($main_event_files);
                }
            }

            if ($employee_exist === true) {
                $events[] = [
                    'id' => $item->id,
                    'date' => Carbon::parse($item->datetime)->format('Y-m-d'),
                    'time' => $item->time,
                    'title' => $item->title,
                    'text' => $item->text,
                    'employee_id' => $item->employee_id,
                    'employees' => $employeess,
                    'files' => $main_event_files
                ];
            }

        }
        return response()->json($events);
    }


    public function allEventsEmployee($query): JsonResponse
    {
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $date = strtotime(Carbon::parse($query->date)->format('Y-m-d'));
        $main_events = MainEvent::with(['employees'])->where('datetime', $date)->where('title', $query->title)->get();
        $events = [];
        $files = MainEventFile::all();

        foreach ($main_events as $item) {
            $employees = [];
            $main_event_files = [];
            $employee_exist = false;
            $employeess = [];

            foreach ($item->employees as $employee) {
                $employees[] = Employee::find($employee->employee_id)->id;
            }

            if (in_array($employee_id, $employees)) {
                $employee_exist = true;
            }

            if ($employee_exist === true) {
                foreach ($files as $file) {
                    foreach (json_decode($file->event_ids) as $id) {
                        if ($id === $item->id) {
                            $main_event_files[] = $file;
                        }
                    }
                }

                if (count($main_event_files) > 0) {
                    $main_event_files = MainEventFileResource::collection($main_event_files);
                }
            }
            foreach ($item->employees as $employee) {
                $employeess[] = Employee::find($employee->employee_id);
            }

            $events[] = [
                'id' => $item->id,
                'date' => Carbon::parse($item->datetime)->format('Y-m-d'),
                'time' => $item->time,
                'employee_id' => $item->employee_id,
                'title' => $item->title,
                'text' => $item->text,
                'employees' => $employeess,
                'files' => $main_event_files
            ];

        }

        return response()->json($events);
    }

    public
    function store($query): JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'dates' => 'required',
                'status' => 'required',
                'employee' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_EXPECTATION_FAILED);
        }
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        $dates = explode(",", $query->dates);
        $employees = explode(",", $query->employee);
        $time = $query->time;
        $status = $query->status;
        $text = $query->text;
        $event_ids = [];
        $event_data = [];
        foreach ($dates as $date) {
            $datetime = Carbon::parse($date)->format("Y-m-d");

            $event = new MainEvent();
            $event->title = $status;
            $event->text = $text;
            $event->datetime = strtotime($datetime);
            $event->time = $time;
            $event->date = Carbon::parse($date)->format("Y-m-d");
            $event->employee_id = $employee->id;

            $event_data = $event;
            if ($event->save()) {
                $event_ids[] = $event->id;
                if (count($employees) > 0) {
                    foreach ($employees as $value) {
                        $this->storeEventEmployee($value, $event->id);
                    }
                }
            }
        }

        if ($query->file('files')) {
            foreach ($query->file('files') as $file) {
                $this->storeFile($file, $event_ids);
            }
        }

        $this->event_mail([
            'event' => $event_data,
            'employees' => $employees
        ]);

        $result = ['message' => 'created successfully'];

        return response()->json($result, Response::HTTP_CREATED);
    }


    public function storeFile($file, $event_ids)
    {
        $filename = $this->file_store_service->store($file, 'events');
        $eventFile = new MainEventFile();
        $eventFile->event_ids = json_encode($event_ids);
        $eventFile->file = $filename;
        $eventFile->save();
    }

    public function storeEventEmployee($value, $event_id)
    {
        $eventEmployee = new MainEventEmployee();
        $eventEmployee->event_id = $event_id;
        $eventEmployee->employee_id = $value;
        $eventEmployee->save();
    }

    public function event_mail($data)
    {

        if (count($data['employees']) > 0) {
            $title = $data['event']['title'];
            $body = "Уведомление о событии \n Дата и время: " . $data['event']['date'] . ' ' . $data['event']['time'] . " \n " . $data['event']['text'];
            foreach ($data['employees'] as $employee) {
                $email = Employee::find($employee)->email;
                $mail = new MailController();
                $mail->event_create($email, $title, $body);

            }
        }
    }

    public function deleteEventsEmployee($id): JsonResponse
    {
        $event = MainEvent::find($id);
        $event_employee = MainEventEmployee::where('event_id', $event->id)->delete();

        $files = MainEventFile::all();
        if (count($files) > 0) {
            foreach ($files as $file) {
                foreach (json_decode($file->event_ids) as $idd) {
                    if ($idd === $event->id) {
                        if (Storage::exists('public/uploads/' . $file->file)) {
                            Storage::delete('public/uploads/' . $file->file);
                        }
                        $file->delete();
                    }
                }
            }
        }

        $event->delete($event->id);

        return response()->json(
            [
                $event_employee,
                'success' => 'event deleted successfully!',
            ],
            Response::HTTP_OK
        );
    }

    public function update($query, $id){
        $event = MainEvent::with('employees')->find($id);

        $event->title = $query->status;
        $event->text = $query->text;
        $event->time = $query->time;
        $employees = $query->employee;

        if($event->save()) {
            if (count($employees) > 0) {
                MainEventEmployee::where('event_id', $event->id)->delete();
                foreach ($employees as $value) {
                    $this->storeEventEmployee($value, $event->id);
                }
            }
        }
        $this->event_mail([
            'event' => $event,
            'employees' => $employees
        ]);

        return response()->json($event, Response::HTTP_OK);
    }

    public function get($id){

        $event = MainEvent::with('employees')->find($id);
        $employees = [];

        foreach ($event->employees as $employee) {
            $employees[] = $employee->employee_id;
        }

        $result = [
            'event' => $event,
            'employees' => $employees
        ];

        return response()->json($result, Response::HTTP_OK);
    }
}
