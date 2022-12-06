<?php

namespace App\Http\Controllers;

use App\Models\DocsAgency;
use Illuminate\Http\Request;

class DocsAgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return DocsAgency::all();
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
     * @param  \App\Models\DocsAgency  $docsAgency
     * @return \Illuminate\Http\Response
     */
    public function show(DocsAgency $docsAgency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocsAgency  $docsAgency
     * @return \Illuminate\Http\Response
     */
    public function edit(DocsAgency $docsAgency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocsAgency  $docsAgency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocsAgency $docsAgency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocsAgency  $docsAgency
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocsAgency $docsAgency)
    {
        //
    }
}
