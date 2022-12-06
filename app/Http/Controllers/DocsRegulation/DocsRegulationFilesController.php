<?php

namespace App\Http\Controllers\DocsRegulation;

use App\Http\Controllers\Controller;
use App\Models\DocsRegulationDynamicFile;
use App\Models\DocsRegulationFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use function response;

class DocsRegulationFilesController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocsRegulationFiles  $docsRegulationFiles
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $doc = DocsRegulationFiles::find($id);

        if (Storage::exists('public/uploads/' . $doc->file)) {

            Storage::delete('public/uploads/' . $doc->file);

            $doc->delete($doc->id);
        }

        $docDynamicFiles = DocsRegulationDynamicFile::where('docs_regulation_file_id', $id)->get();

        if ($docDynamicFiles->count() > 0) {
            foreach ($docDynamicFiles as $item) {
                if (Storage::exists('public/uploads/' . $item->file)) {
                    Storage::delete('public/uploads/' . $item->file);
                    Storage::deleteDirectory('public/uploads/docs/dynamic/regulation/' . $item->id);
                }
                $docsDynamicFileItem = DocsRegulationDynamicFile::find($item->id);
                $docsDynamicFileItem->delete($item->id);
            }
        }

        return response()->json('deleted successfully!', Response::HTTP_OK);

    }
}
