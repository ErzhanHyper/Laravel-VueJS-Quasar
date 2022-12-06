<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\ApplicationCategory;
use Illuminate\Http\Request;
use function response;

class ApplicationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $category = ApplicationCategory::all();
        return response()->json($category);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationCategory  $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApplicationCategory  $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApplicationCategory  $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationCategory $applicationCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationCategory  $applicationCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationCategory $applicationCategory)
    {
        //
    }
}
