<?php

namespace App\Services\DocsService\DocsRegulation;

use App\Http\Resources\DocsRegulationAdditionFileResource;
use App\Http\Resources\DocsRegulationDynamicFileResource;
use App\Http\Resources\DocsRegulationFileResource;
use App\Http\Resources\DocsRegulationResource;
use App\Http\Resources\EmployeeResource;
use App\Models\DocsRegulation;
use App\Models\DocsRegulationDynamicFile;
use App\Models\DocsRegulationFileAddition;
use App\Models\DocsRegulationFiles;
use App\Models\DocsRegulationViewer;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Imagick;
use function auth;
use function public_path;
use function response;

class DocsRegulationService
{

    public function list($query): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $docs = DocsRegulation::with('docs_regulation_file')->select(['docs_regulations.*'], DB::raw('COUNT(docs_regulation_files.id) as total_responses'), DB::raw('MAX(CAST(docs_regulation_files.date_start AS CHAR)) as latest'))
            ->leftjoin('docs_regulation_files', function ($join) {
                $join->on('docs_regulation_files.docs_regulation_id', '=', 'docs_regulations.id');
            })
            ->groupby('docs_regulations.id')->orderByDesc('created_at');

        $name = $query->name;
        $agency = $query->agency;
        $date_approve = $query->date_approve;
        $date_start = $query->date_start;
        $file_type = $query->file_type_id;
        $department = $query->department_id;

        if ($name) {
            $docs->where('name', 'like', '%' . $name . '%');
        }
        if ($agency) {
            $docs->where('docs_regulation_files.agency', 'like', '%' . $agency . '%');
        }
        if ($date_approve) {
            $docs->where('docs_regulation_files.date_approve', 'like', '%' . strtotime($date_approve) . '%');
        }
        if ($date_start) {
            $docs->where('docs_regulation_files.date_start', 'like', '%' . strtotime($date_start) . '%');
        }
        if ($file_type) {
            $docs->where('file_type_id', $file_type);
        }
        if ($department) {
            $docs->where('docs_regulation_files.department_id', $department);
        }

        return DocsRegulationResource::collection($docs->paginate(10));
    }

    public function updateDocs($query, $id): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'file' => 'required|mimes:pdf',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $docs = DocsRegulation::find($id);
        $docsDynamic = DocsRegulationDynamicFile::where('docs_regulation_id', $docs->id)->first();

        $file = $query->file;
        $filename = $this->storeFile($file);
        $agency = $query->agency;
        $department_id = $query->department_id;
        $date_approve = $query->date_approve;
        $date_start = $query->date_start;

        $docsFile = new DocsRegulationFiles;
        $docsFile->name = $docs->name;
        $docsFile->file = $filename;
        $docsFile->docs_regulation_id = $docs->id;
        $docsFile->employee_id = $employee_id;
        $docsFile->agency = $agency;
        $docsFile->department_id = $department_id;
        $docsFile->date_approve = strtotime($date_approve);
        $docsFile->date_start = strtotime($date_start);

        if ($docsFile->save()) {
            $this->convertToPDF($docsFile);

            if ($query->addition_files) {
                if (count($_FILES['addition_files']['name']) > 0) {
                    for ($i = 0; $i < count($_FILES['addition_files']['name']); $i++) {
                        $filename = time() . '_' . $_FILES['addition_files']['name'][$i];
                        $path = public_path('storage/uploads/docs/addition/regulation/' . $filename);
                        move_uploaded_file($_FILES['addition_files']['tmp_name'][$i], $path);
                        $addition = new DocsRegulationFileAddition;
                        $addition->name = $query->addition_names[$i];
                        $addition->file = 'docs/addition/regulation/' . $filename;
                        $addition->docs_regulation_id = $docs->id;
                        $addition->docs_regulation_file_id = $docsFile->id;
                        $addition->save();
                    }
                }
            }


            if ($query->dynamic_file) {
                if (!is_string($query->dynamic_file)) {
                    $docsDynamicFilename = $this->storeDynamicFile($query->dynamic_file);;
                    if (!$docsDynamic) {
                        $docsDynamic = new DocsRegulationDynamicFile();
                    }
                    $docsDynamic->file = $docsDynamicFilename;
                    $docsDynamic->docs_regulation_id = $docs->id;
                    $docsDynamic->docs_regulation_file_id = $docsFile->id;
                    $docsDynamic->save();
                }
            }

        }

        return response()->json(
            [
                'result' => 'updated',
            ],
            Response::HTTP_OK
        );

    }

    public function update($query, $id): \Illuminate\Http\JsonResponse
    {
        $docs = DocsRegulation::find($id);
        $docsFile = DocsRegulationFiles::where('docs_regulation_id', $id)->orderByDesc('date_start')->first();
        $docsDynamic = DocsRegulationDynamicFile::where('docs_regulation_id', $docs->id)->first();
        if ($query->name) {
            $docs->name = $query->name;
        }
        if ($query->agency) {
            $docsFile->agency = $query->agency;
        }
        if ($query->date_approve) {
            $docsFile->date_approve = strtotime($query->date_approve);
        }
        if ($query->date_start) {
            $docsFile->date_start = strtotime($query->date_start);
        }
        if ($query->department_id) {
            $docsFile->department_id = $query->department_id;
        }

        if (is_string($query->department_id)) {
            $docsFile->department_id = 0;
        }


        if ($query->file_type_id) {
            $docs->file_type_id = $query->file_type_id;
        }

        if ($query->file) {
            if ($query->file !== '' || $query->file !== null) {
                $filename = $this->storeFile($query->file);
                $docsFile->file = $filename;
            }
        }

        if ($query->dynamic_file) {
            if ($query->dynamic_file !== '' || $query->dynamic_file !== null) {
                if (!$docsDynamic) {
                    $docsDynamic = new DocsRegulationDynamicFile();
                }
                $docsDynamicFilename = $this->storeDynamicFile($query->dynamic_file);;
                $docsDynamic->file = $docsDynamicFilename;
                $docsDynamic->docs_regulation_id = $docs->id;
                $docsDynamic->docs_regulation_file_id = $docsFile->id;
                $docsDynamic->save();
            }
        }
        if ($query->addition_files) {
            if (isset($_FILES['addition_files']) && count($_FILES['addition_files']['name']) > 0) {
                for ($i = 0; $i < count($_FILES['addition_files']['name']); $i++) {
                    $filename = time() . '_' . $_FILES['addition_files']['name'][$i];
                    $path = public_path('storage/uploads/docs/addition/regulation/' . $filename);
                    move_uploaded_file($_FILES['addition_files']['tmp_name'][$i], $path);
                    $addition = new DocsRegulationFileAddition;
                    $addition->name = $query->addition_names[$i];
                    $addition->file = 'docs/addition/regulation/' . $filename;
                    $addition->docs_regulation_id = $docs->id;
                    $addition->docs_regulation_file_id = $docsFile->id;
                    $addition->save();
                }
            }
        }


        if ($docs->save()) {
            $docsFile->save();
        }

        return response()->json(
            [
                'result' => new DocsRegulationResource($docs),
            ],
            Response::HTTP_OK
        );

    }

    public function store($query): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'file' => 'required|mimes:pdf',
                'name' => 'required|max:512'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $docs = new DocsRegulation();

        $name = $query->name;
        $agency = $query->agency;
        $date_approve = null;
        $date_start = null;

        if ($query->date_start) {
            $date_start = strtotime(Carbon::parse($query->date_start));
        }
        if ($query->date_approve) {
            $date_approve = strtotime(Carbon::parse($query->date_approve));
        }
        $file_type = 15;
        if ((int)$query->file_type_id) {
            $file_type = (int)$query->file_type_id;
        }

        $file = $query->file;
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $department_id = Employee::where('user_id', auth()->user()->id)->get()[0]->department_id;

        if ($query->department_id !== '' || $query->department_id !== 0) {
            $department_id = (int)$query->department_id;
        }

        $docs->name = $name;
        $docs->file_type_id = $file_type;

        if ($docs->save()) {
            if ($file) {
                $filename = $this->storeFile($file);

                $docsFile = new DocsRegulationFiles;
                $docsFile->name = $name;
                $docsFile->agency = $agency;
                $docsFile->file = $filename;
                $docsFile->docs_regulation_id = $docs->id;
                $docsFile->date_start = $date_start;
                $docsFile->date_approve = $date_approve;
                $docsFile->department_id = $department_id;
                $docsFile->employee_id = $employee_id;

                if ($docsFile->save()) {
                    $this->convertToPDF($docsFile);

                    if ($query->dynamic_file && $query->file('dynamic_file')) {
                        $docsDynamicFilename = $this->storeDynamicFile($query->dynamic_file);
                        $docsDynamic = new DocsRegulationDynamicFile();
                        $docsDynamic->file = $docsDynamicFilename;
                        $docsDynamic->docs_regulation_id = $docs->id;
                        $docsDynamic->docs_regulation_file_id = $docsFile->id;
                        $docsDynamic->save();
                    }

                    if ($query->addition_files) {
                        if (isset($_FILES['addition_files']) && count($_FILES['addition_files']['name']) > 0) {
                            for ($i = 0; $i < count($_FILES['addition_files']['name']); $i++) {
                                $filename = time() . '_' . $_FILES['addition_files']['name'][$i];
                                $path = public_path('storage/uploads/docs/addition/regulation/' . $filename);
                                move_uploaded_file($_FILES['addition_files']['tmp_name'][$i], $path);
                                $addition = new DocsRegulationFileAddition;
                                $addition->name = $query->addition_names[$i];
                                $addition->file = 'docs/addition/regulation/' . $filename;
                                $addition->docs_regulation_id = $docs->id;
                                $addition->docs_regulation_file_id = $docsFile->id;
                                $addition->save();
                            }
                        }
                    }
                }
            }
        }

        $result = new DocsRegulationResource($docs);

        return response()->json($result, Response::HTTP_OK);

    }

    public function get($id): \Illuminate\Http\JsonResponse
    {
        $docs = DocsRegulation::with(['docs_regulation_file', 'viewers'])->find($id);
        $addition = DocsRegulationFileAddition::where('docs_regulation_id', $docs->id)->orderByDesc('created_at')->get();
        $dynamic_file = DocsRegulationDynamicFile::where('docs_regulation_id', $docs->id)->get();

        if (count($dynamic_file) > 0) {
            $dynamic_file = new DocsRegulationDynamicFileResource($dynamic_file[0]);
        }

        $employees = [];
        $viewers = DocsRegulationViewer::where('docs_regulation_id', $id)->get();
        foreach ($viewers as $viewer) {
            $employees[] = new EmployeeResource(Employee::find($viewer->employee_id));
        }
        $result = [
            'viewers' => $employees,
            'docs' => new DocsRegulationResource($docs),
            'dynamic_file' => $dynamic_file,
            'addition_files' => DocsRegulationAdditionFileResource::collection($addition),
        ];

        return response()->json($result, Response::HTTP_OK);

    }

    public function storeViewer($query): \Illuminate\Http\JsonResponse
    {
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $viewer = DocsRegulationViewer::where('employee_id', $employee_id)->where('docs_regulation_id', $query->id);
        $viewed = false;
        if($viewer->count() >0 ){
            $viewed = true;
        }

        if(!$viewed) {
            $viewer = new DocsRegulationViewer;
            $viewer->docs_regulation_id = $query->id;
            $viewer->employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
            $viewer->save();
            $result = ['created successfully!'];
        }else{
            $result = ['also exist!'];
        }

        return response()->json($result, Response::HTTP_OK);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $docs = DocsRegulation::find($id);
        $docsFile = DocsRegulationFiles::where('docs_regulation_id', $id)->get();
        $docsDynamicFile = DocsRegulationDynamicFile::where('docs_regulation_id', $docs->id)->get();
        $docsAdditionFile = DocsRegulationFileAddition::where('docs_regulation_id', $docs->id)->get();

        if ($docsFile->count() > 0) {
            foreach ($docsFile as $item) {
                if (Storage::exists('public/uploads/' . $item->file)) {
                    Storage::delete('public/uploads/' . $item->file);
                    Storage::deleteDirectory('public/uploads/docs/image/regulation/' . $item->id);
                }
                $docsFileItem = DocsRegulationFiles::find($item->id);
                $docsFileItem->delete($item->id);
            }
        }

        if ($docsDynamicFile->count() > 0) {
            foreach ($docsDynamicFile as $item) {
                if (Storage::exists('public/uploads/' . $item->file)) {
                    Storage::delete('public/uploads/' . $item->file);
                    Storage::deleteDirectory('public/uploads/docs/dynamic/regulation' . $item->id);
                }
                $docsDynamicFileItem = DocsRegulationDynamicFile::find($item->id);
                $docsDynamicFileItem->delete($item->id);
            }
        }

        if ($docsAdditionFile->count() > 0) {
            foreach ($docsAdditionFile as $item) {
                if (Storage::exists('public/uploads/' . $item->file)) {
                    Storage::delete('public/uploads/' . $item->file);
                    Storage::deleteDirectory('public/uploads/docs/addition/regulation' . $item->id);
                }
                $docsAdditionFileItem = DocsRegulationFileAddition::find($item->id);
                $docsAdditionFileItem->delete($item->id);
            }
        }

        $docs->delete($id);

        $result = ['deleted successfully'];

        return response()->json($result, Response::HTTP_OK);
    }

    public function convertToPDF($data)
    {
        $path = 'storage/uploads/' . $data->file;
        $imagick = new Imagick();
        $imagick->setResolution(144, 144);
        $imagick->readImage(public_path($path));
        Storage::disk('public')->makeDirectory('/uploads/docs/image/regulation/' . $data->id);
        $saveImagePath = public_path('/storage/uploads/docs/image/regulation/' . $data->id . '/' . $data->id . '_converted.jpg');
        $imagick->writeImages($saveImagePath, true);
    }

    public function storeFile($file): string
    {
        $original_name = time() . '_' . $file->getClientOriginalName();
        $filename = 'docs/regulation/' . $original_name;
        $file->move(public_path('storage/uploads/docs/regulation'), $original_name);
        return $filename;
    }

    public function storeDynamicFile($file): string
    {
        $original_name = time() . '_' . $file->getClientOriginalName();
        $filename = 'docs/dynamic/regulation/' . $original_name;
        $file->move(public_path('storage/uploads/docs/dynamic/regulation'), $original_name);
        return $filename;
    }

    public function storeFileAddition($file): string
    {
        $original_name = time() . '_' . $file->getClientOriginalName();
        $filename = 'docs/addition/regulation' . $original_name;
        $file->move(public_path('storage/uploads/docs/addition/regulation'), $original_name);
        return $filename;
    }

    public function docsImage($id): \Illuminate\Http\JsonResponse
    {
        $docs = DocsRegulation::find($id);
        $docs_file = DocsRegulationFiles::where('docs_regulation_id', $id)->orderByDesc('created_at')->with('employee')->first();

        $file_url2 = 'public/uploads/docs/image/regulation/' . $docs_file->id;
        $docs_file = DocsRegulationFiles::where('docs_regulation_id', $id)->orderByDesc('created_at')->with('employee')->first();
        $storage = Storage::allFiles($file_url2);

        $urls = [];
        foreach ($storage as $key => $item) {
            $name = substr($item, strrpos($item, '/') + 1);
            $urls[] = [
                'key' => preg_replace('/[^\p{L}\p{N}\s]/u', '', substr($name, -6, 2)),
                'data' => config('app.url') . '/storage/uploads/docs/image/regulation/' . $docs_file->id . '/' . $name
            ];
        }
        $array = json_decode(json_encode($urls), true);
        sort($array);

        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $viewer = DocsRegulationViewer::where('employee_id', $employee_id)->where('docs_regulation_id', $id);
        $viewed = false;
        if($viewer->count() >0 ){
            $viewed = true;
        }
        return response()->json([
            'viewed' => $viewed,
            'docs' => $docs,
            'path' => $array
        ]);
    }


    public function library($id): \Illuminate\Http\JsonResponse
    {
        $docs_file = DocsRegulationFiles::where('docs_regulation_id', $id)->with(['employee', 'additionFile'])->orderByDesc('date_start')->get();

        $docs_collection = [];
        foreach ($docs_file as $key => $item) {
            $docs_collection[] = new DocsRegulationFileResource([$docs_file[$key]]);
        }

        return response()->json($docs_collection);
    }

}
