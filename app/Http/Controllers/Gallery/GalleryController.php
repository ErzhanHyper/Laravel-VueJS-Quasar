<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use App\Models\GalleryCatalog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use function config;
use function public_path;
use function response;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $gallery = Gallery::where('gallery_catalog_id', $id)->get();
        $catalog = GalleryCatalog::find($id);

        return response()->json([
            'gallery' => GalleryResource::collection($gallery),
            'catalog' => $catalog
        ]);
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->file('files') as $key => $f) {
            $original_name = time() . '_' . $f->getClientOriginalName();
            $filename = 'gallery/' . $original_name;
            $f->move(public_path('storage/uploads/gallery'), $original_name);
            $file = new Gallery;
            $file->gallery_catalog_id = $request->catalog;
            $file->file = $filename;
            $file->save();
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
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::where('gallery_catalog_id', $id)->get();
        $catalog = GalleryCatalog::find($id);

        $catalog->delete($catalog->id);
        foreach ($gallery as $item) {
            $item->delete($item->id);
            if (Storage::exists('public/uploads/' . $item->file)) {
                Storage::delete('public/uploads/' . $item->file);
            }
        }

        return response()->json(
            [
                'success' => 'Gallery file deleted successfully!',
            ],
            Response::HTTP_CREATED
        );
    }
}
