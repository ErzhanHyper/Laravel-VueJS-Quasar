<?php

namespace App\Services\DocsService\DocsTrust;

use App\Http\Resources\DocsTrustFileResource;
use App\Http\Resources\DocsTrustResource;
use App\Models\DocsTrust;
use App\Models\DocsTrustFile;
use App\Models\Employee;
use App\Services\File\FileStore;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class DocsTrustService
{
    private FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }


    public function docsTrustFileStore($query, $id)
    {
        $path = 'docs/trust';
        $docsTrustFile = new DocsTrustFile();
        $docsTrustFile->employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $docsTrustFile->warrant_id = $query->warrant_id.'-C';
        $docsTrustFile->direction = $query->direction;
        $docsTrustFile->date = $query->date;
        $docsTrustFile->date_expiration_start = $query->date_expiration_start;
        $docsTrustFile->date_expiration_end = $query->date_expiration_end;
        $docsTrustFile->department_id = $query->department_id;
        $docsTrustFile->docs_trust_id = $id;
        $docsTrustFile->file = $this->file_store_service->store($query->file, $path);
        $docsTrustFile->profession_id = $query->profession_id;
        $docsTrustFile->agent = $query->agent;
        $docsTrustFile->docs_type = $query->docs_type;
        $docsTrustFile->save();
    }

    public function list($query): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $docsTrust = DocsTrust::with(['docs_trust_file', 'employee', 'department'])->orderByDesc('created_at');

        if ($query->name) {
            $docsTrust->where('name', 'like', '%' . $query->name . '%');
        }

        return DocsTrustResource::collection($docsTrust->paginate(10));
    }

    public function history($id): JsonResponse
    {
        $docs_file = DocsTrustFile::where('docs_trust_id', $id)->orderByDesc('date')->get();
        return response()->json(DocsTrustFileResource::collection($docs_file));
    }

    public function store($query): JsonResponse
    {
        $validator = Validator::make($query->all(),
            [
                'name' => 'required|max:512',
                'file' => 'required|mimes:pdf,docx',
                'warrant_id' => 'required|numeric',
                'department_id' => 'required|numeric',
                'date' => 'required|numeric',
                'date_expiration_start' => 'required|numeric',
                'date_expiration_end' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_EXPECTATION_FAILED);
        }

        $docsTrust = new DocsTrust();
        $docsTrust->name = $query->name;
        if ($docsTrust->save()) {
            $this->docsTrustFileStore($query, $docsTrust->id);
        }

        $result = new DocsTrustResource($docsTrust);

        return response()->json($result, Response::HTTP_OK);
    }

    public function update($query, $id): JsonResponse
    {
        $validator = Validator::make($query->all(),
            [
                'file' => 'required|mimes:pdf,docx',
                'warrant_id' => 'required|numeric',
                'department_id' => 'required|numeric',
                'date' => 'required|numeric',
                'date_expiration_start' => 'required|numeric',
                'date_expiration_end' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_EXPECTATION_FAILED);
        }

        $docsTrust = DocsTrust::find($id);
        $this->docsTrustFileStore($query, $docsTrust->id);

        $result = new DocsTrustResource($docsTrust);

        return response()->json($result, Response::HTTP_OK);
    }

    public function delete($id): JsonResponse
    {
        $docsTrust = DocsTrust::find($id);
        if (Storage::exists('public/uploads/' . $docsTrust->file)) {
            Storage::delete('public/uploads/' . $docsTrust->file);
        }
        $docsTrust->delete($docsTrust->id);

        $result = 'record deleted successfully';

        return response()->json($result, Response::HTTP_OK);
    }

    public function get($id): JsonResponse
    {
        $docs = DocsTrust::find($id);
        $result = new DocsTrustResource($docs);

        return response()->json($result, Response::HTTP_OK);
    }

}


?>
