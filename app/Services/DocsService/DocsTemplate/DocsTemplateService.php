<?php

namespace App\Services\DocsService\DocsTemplate;

use App\Http\Resources\DocsTemplateFileResource;
use App\Http\Resources\DocsTemplateResource;
use App\Models\DocsTemplate;
use App\Models\DocsTemplateFiles;
use App\Models\Employee;
use App\Services\File\FileStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocsTemplateService
{

    private FileStore $file_store_service;
    private string $path = 'docs/template';

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function list($query): JsonResponse
    {
        $docs = DocsTemplate::orderBy('created_at', 'desc')->with('docs_template_file');

        if ($query->search) {
            $docs = $docs->where('name', 'like', '%' . $query->search . '%')->paginate(20);
        }

        return response()->json(DocsTemplateResource::collection($docs->paginate(20)));
    }

    public function get($id): JsonResponse
    {
        $docs = DocsTemplate::find($id);
        $docs_file = DocsTemplateFiles::where('docs_template_id', $id)->orderByDesc('created_at')->with('employee')->first();

        $result = [
            'docs' => new DocsTemplateResource($docs),
            'file' => new DocsTemplateFileResource($docs_file)
        ];

        return response()->json($result, Response::HTTP_OK);
    }

    public function history($id): JsonResponse
    {
        $docs_file_last = DocsTemplateFiles::where('docs_template_id', $id)->orderByDesc('created_at')->with('employee')->first();
        $last_date = $docs_file_last->created_at;
        $docs_file = DocsTemplateFiles::where('docs_template_id', $id)->whereNotIn('created_at', [$last_date])->with('employee')->get();

        return response()->json($docs_file, Response::HTTP_OK);
    }

    public function store($query): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->first()->id;

        $docs = new DocsTemplate();
        $docs->name = $query->name;
        $docs->file_type_id = 1;

        if($docs->save()){
            $files = new DocsTemplateFiles;
            $files->file = $this->filename($query);
            $files->docs_template_id = $docs->id;
            $files->employee_id = $employee_id;
            $files->save();
        }

        return response()->json($docs, Response::HTTP_OK);
    }

    public function upload($query, $id)
    {
        $docs = DocsTemplate::find($id);

        if($docs->save()){
            $files = new DocsTemplateFiles;
            $files->file = $this->filename($query);
            $files->docs_template_id = $docs->id;
            $files->employee_id = $query->employee_id;
            $files->save();
        }

        return response()->json($docs, Response::HTTP_OK);

    }

    public function delete($query, $id): JsonResponse
    {
        $type = $query->type;

        if($type == 'file') {
            $docs = DocsTemplateFiles::find($id);
            if (Storage::exists('public/uploads/' . $docs->file)) {
                Storage::delete('public/uploads/' . $docs->file);
            }
        }else{
            $docs = DocsTemplate::find($id);
            $docs_files = DocsTemplateFiles::where('docs_template_id', $id)->get();
            foreach ($docs_files as $item){
                $docs_file = DocsTemplateFiles::find($item->id);
                $docs_file->delete($item->id);
                if (Storage::exists('public/uploads/' . $item->file)) {
                    Storage::delete('public/uploads/' . $item->file);
                }
            }
        }
        $docs->delete($docs->id);

        $result = 'record deleted successfully';

        return response()->json($result, Response::HTTP_OK);
    }


    public function filename($query): string
    {
       return $this->file_store_service->store($query->file, $this->path);
    }
}
