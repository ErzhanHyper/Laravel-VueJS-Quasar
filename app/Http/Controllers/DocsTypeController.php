<?php

namespace App\Http\Controllers;

use App\Models\DocsType;
use Illuminate\Http\Request;

class DocsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return DocsType::all();
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
     * @param  \App\Models\DocsType  $docsType
     * @return \Illuminate\Http\Response
     */
    public function show(DocsType $docsType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocsType  $docsType
     * @return \Illuminate\Http\Response
     */
    public function edit(DocsType $docsType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocsType  $docsType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocsType $docsType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocsType  $docsType
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocsType $docsType)
    {
        //
    }
}
