<?php

namespace App\Http\Controllers\Polling;

use App\Http\Controllers\Controller;
use App\Models\PollingChoice;
use Illuminate\Http\Request;
use function response;

class PollingChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $oldChoice = PollingChoice::where('polling_id', $request->polling_id)->where('employee_id', $request->employee_id);
        $oldChoice->delete();

        $choice = new PollingChoice();
        $choice->employee_id = $request->employee_id;
        $choice->polling_id = $request->polling_id;
        $choice->select = $request->select;
        $choice->save();

        return response()->json($choice);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PollingChoice $pollingChoice
     * @return \Illuminate\Http\Response
     */
    public function show(PollingChoice $pollingChoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PollingChoice $pollingChoice
     * @return \Illuminate\Http\Response
     */
    public function edit(PollingChoice $pollingChoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PollingChoice $pollingChoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PollingChoice $pollingChoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PollingChoice $pollingChoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(PollingChoice $pollingChoice)
    {
        //
    }
}
