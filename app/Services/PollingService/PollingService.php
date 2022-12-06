<?php

namespace App\Services\PollingService;

use App\Http\Resources\PollingResource;
use App\Models\Employee;
use App\Models\Polling;
use App\Models\PollingChoice;
use App\Models\PollingComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PollingService
{

    public function choiceCount($id): JsonResponse
    {
        $polling = Polling::find($id);
        $choices = [];
        $total = PollingChoice::where('polling_id', $id)->count();
        $types = [];

        foreach (json_decode($polling->types) as $type) {
            $types[] = $type->type;
        }

        for ($i = 0; $i < count($types); $i++) {
            $count = PollingChoice::where('polling_id', $id)->where('select', $types[$i])->count();
            $percent = ((int)$count / (int)$total) * 100;
            $choices[] = [
                'type' => $types[$i],
                'count' => $percent,
            ];
        }

        return response()->json($choices);
    }

    public function list(): JsonResponse
    {
        $polling = Polling::paginate(10);
        return response()->json($polling);
    }

    public function get($id): JsonResponse
    {
        $selected = false;
        $employee = Employee::where('user_id', auth()->user()->id);
        $polling = Polling::with('polling_choice')->find($id);
        $pollingChoice = [];
        if ($employee->count() > 0) {
            $pollingChoice = PollingChoice::where('polling_id', $id)->where('employee_id', $employee->get()[0]->id);
            if ($pollingChoice->count() > 0) {
                $selected = true;
                $pollingChoice = $pollingChoice->get()[0];
            }
        }

        return response()->json([
            'polling' => new PollingResource($polling),
            'selected' => $selected,
            'polling_choice' => $pollingChoice,
        ]);
    }

    public function last(): JsonResponse
    {
        $polling = Polling::orderByDesc('created_at')->first();
        return response()->json($polling);
    }

    public function store(Request $request): JsonResponse
    {
        $polling = new Polling();
        $polling->title = $request->title;
        $polling->description = $request->description;
        $polling->types = json_encode($request->types);
        $polling->status = 0;
        $polling->save();

        return response()->json($polling);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $polling = Polling::find($id);

        if ($request->status === true) {
            $publish = 1;
        } else {
            $publish = 0;
        }
        $polling->status = $publish;
        if ($request->title) {
            $polling->title = $request->title;
        }
        if ($request->description) {
            $polling->description = $request->description;
        }
        if ($request->types) {
            $polling->types = json_encode($request->types);
        }
        $polling->save();

        return response()->json($polling);
    }

    public function destroy($id): JsonResponse
    {
        $polling = Polling::find($id);
        $polling->delete($polling->id);

        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

}
