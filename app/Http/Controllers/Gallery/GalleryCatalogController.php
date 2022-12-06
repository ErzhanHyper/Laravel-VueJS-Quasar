<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Models\GalleryCatalog;
use Illuminate\Http\Request;
use function response;

class GalleryCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $catalog = GalleryCatalog::all();
        return response()->json($catalog);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $catalog = new GalleryCatalog;
        $catalog->name = $request->catalog;

        $catalog->save();

        return response()->json($catalog);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GalleryCatalog  $galleryCatalog
     * @return \Illuminate\Http\Response
     */
    public function show(GalleryCatalog $galleryCatalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GalleryCatalog  $galleryCatalog
     * @return \Illuminate\Http\Response
     */
    public function edit(GalleryCatalog $galleryCatalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GalleryCatalog  $galleryCatalog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GalleryCatalog $galleryCatalog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GalleryCatalog  $galleryCatalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleryCatalog $galleryCatalog)
    {
        //
    }
}
