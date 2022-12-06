<?php

namespace App\Services\DocsService\DocsRegulation;

use App\Http\Resources\DocsRegulationAdditionFileResource;
use App\Models\DocsRegulationFileAddition;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function auth;
use function public_path;
use function response;

class DocsRegulationAdditionService
{

    public function history($id): \Illuminate\Http\JsonResponse
    {
        $docs_file_last = DocsRegulationFileAddition::where('docs_regulation_id', $id)->orderByDesc('created_at')->first();
        $last_date = $docs_file_last->created_at;
        $docs_file = DocsRegulationFileAddition::where('docs_regulation_id', $id)->whereNotIn('created_at', [$last_date])->orderByDesc('created_at')->get();

        $docs_collection = [];
        foreach ($docs_file as $key => $item) {
            $docs_collection[] = new DocsRegulationAdditionFileResource($item);
        }
        return response()->json($docs_collection);
    }

    public function get($id){
        $docs = DocsRegulationFileAddition::where('docs_regulation_id', $id)->orderByDesc('created_at')->first();
        return response()->json(new DocsRegulationAdditionFileResource($docs));
    }

    public function library($id){
        $docs = DocsRegulationFileAddition::where('docs_regulation_id', $id)->orderByDesc('created_at')->first();
        return response()->json(new DocsRegulationAdditionFileResource($docs));
    }

    public function store($query): \Illuminate\Http\JsonResponse
    {

        $file = $query->file;
        $docs_regulation_id = $query->docs_regulation_id;
        $department_id = $query->department_id;

        $validator = Validator::make($query->all(),
            [
                'file' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $filename = $this->storeFile($file);

        try {
            $docs = new DocsRegulationFileAddition;
            $docs->employee_id = $employee_id;
            $docs->file = $filename;
            $docs->department_id = $department_id;
            $docs->docs_regulation_id = $docs_regulation_id;
            $docs->save();

            $result = [
                'result' => 'created successfully!',
            ];

            return response()->json($result, Response::HTTP_OK);

        } catch (exception $e) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

    }

    public function storeFile($file)
    {
        $original_name = time() . '_' . $file->getClientOriginalName();
        $filename = 'docs/regulation/dynamic/' . $original_name;
        $file->move(public_path('storage/uploads/docs/dynamic/regulation/'), $original_name);
        return $filename;
    }
}
