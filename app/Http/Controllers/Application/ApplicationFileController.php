<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\ApplicationFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function config;
use function public_path;
use function response;

class ApplicationFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
        if($request->file('files')) {
            foreach ($request->file('files') as $key => $f) {
                $original_name = time() . '_' . $f->getClientOriginalName();
                $extension = $f->getClientOriginalExtension();
                $filename = config('app.url') . '/storage/uploads/application/' . $original_name;
                $f->move(public_path('storage/uploads/application'), $original_name);
                $file = new ApplicationFile;
                $file->application_id = $request->application_id;
                $file->file = $filename;
                $file->name = $original_name;
                $file->file_type = $extension;
                $file->save();
            }
        }

        return response()->json(
            [
                'success' => 'New application file created successfully!',
            ],
            Response::HTTP_CREATED
        );

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ApplicationFile $applicationFile
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationFile $applicationFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ApplicationFile $applicationFile
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationFile $applicationFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ApplicationFile $applicationFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationFile $applicationFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ApplicationFile $applicationFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationFile $applicationFile)
    {
        //
    }
}
