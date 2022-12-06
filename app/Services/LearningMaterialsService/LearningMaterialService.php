<?php

namespace App\Services\LearningMaterialsService;

use App\Http\Resources\LearningResource;
use App\Models\Employee;
use App\Models\LearningMaterial;
use App\Models\LearningMaterialCatalog;
use App\Models\LearningViewer;
use App\Services\File\FileStore;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LearningMaterialService
{

    private FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function getList(): JsonResponse
    {
        $result = LearningMaterial::with(['catalogs', 'file_type'])->paginate(10);
        return response()->json(LearningResource::collection($result), Response::HTTP_OK);
    }

    public function getDetail($id): JsonResponse
    {
        $result = LearningMaterial::with(['catalogs', 'file_type'])->where('id', $id)->first();

        if ($result) {
            return response()->json($result, Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'error' => " Id с таким #$id обучающий материал не найден в базе!",
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    public function create($req): JsonResponse
    {
        $validator = Validator::make($req->all(),
            [
                'name' => 'required',
                'file' => 'required|mimes:pdf,docx,jpeg,png,jpg',
            ],
            [
                'required' => 'Поле :attribute не может быть пустым.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        } else {

            $filename = $this->file_store_service->store($req->file, 'docs/learning');

            $learningMaterial = new LearningMaterial();
            $learningMaterial->file = $filename;
            $learningMaterial->name = $req->name;
            $learningMaterial->file_type_id = 1;
            $learningMaterial->catalog_id = $req->catalog_id;
            $learningMaterial->save();

            return response()->json(
                [
                    'success' => " Обучающий материал успешно добавлен!",
                ],
                Response::HTTP_OK
            );
        }
    }

    public function update($req, $id)
    {
        $validator = Validator::make($req->all(),
            [
                'file_type_id' => 'required|int',
                'catalog_id' => 'required|int',
                'file' => 'required|mimes:pdf,docx,jpeg,png,jpg',
            ],
            [
                'required' => 'Поле :attribute не может быть пустым.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'messages' => [$validator->messages()],
                ],
                Response::HTTP_EXPECTATION_FAILED
            );
        } else {

            $learningMaterial = LearningMaterial::find($id);
            if ($learningMaterial) {
                $folder = config('app.url') . '/storage/uploads/docs/learning';

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                $file = $req->file;
                $original_name = time() . '_' . $file->getClientOriginalName();
                $filename = $original_name;
                $file->move(public_path($folder), $original_name);

                if (File::exists(public_path($learningMaterial->file))) {
                    File::delete(public_path($learningMaterial->file));
                }

                $learningMaterial->file = $filename;
                $learningMaterial->file_type_id = $req->file_type_id;
                $learningMaterial->catalog_id = $req->catalog_id;
                $learningMaterial->save();

                return response()->json(
                    [
                        'success' => " Обучающий материал успешно обновлен!",
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        'error' => " Обучающий материал с таким Id: #$id не найден!",
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }
        }
    }

    public function delete($id): JsonResponse
    {
        $learningMaterial = LearningMaterial::findOrFail($id);
        if (File::exists(public_path($learningMaterial->file))) {
            File::delete(public_path($learningMaterial->file));
        }
        $learningMaterial->delete();

        return response()->json(
            [
                'success' => " Обучающий материал успешно удален!",
            ],
            Response::HTTP_OK
        );
    }

    public function storeCatalog($query): JsonResponse
    {
        $catalog = new LearningMaterialCatalog();
        $catalog->name = $query->catalog;
        $catalog->description = $query->catalog;

        $catalog->save();
        return response()->json(
            $catalog,
            Response::HTTP_OK
        );
    }

    public function catalog(): JsonResponse
    {
        $catalog = LearningMaterialCatalog::all();
        return response()->json($catalog, Response::HTTP_OK);
    }

    public function catalogDetail($id): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;

        $catalog = LearningMaterialCatalog::with('learning_material')->find($id);

        $files = collect();

        foreach ($catalog->learning_material as $item){
            $checked = LearningViewer::where('learning_id', $item->id)->where('employee_id', $employee_id)->get();
            if(count($checked) > 0){
                $checked = true;
            }else{
                $checked = false;
            }
            $item->file = config('app.url') . '/storage/uploads/'.$item->file;
            $item->checked = $checked;
            $files->push($item);
        }

        $result = [
            'catalog' => $catalog,
            'files' => $files,
        ];
        return response()->json($result, Response::HTTP_OK);
    }


    public function catalogDelete($id): JsonResponse
    {
        $catalog = LearningMaterialCatalog::find($id);
        $files = LearningMaterial::where('catalog_id', $id)->delete();
        $catalog->delete();
        $result = ['succsess' => 'success'];
        return response()->json($result, Response::HTTP_OK);
    }

    public function storeViewer($query): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $viewer = new LearningViewer();
        $viewer->learning_id = $query->learning_id;
        $viewer->employee_id = $employee_id;
        $viewer->save();

        return response()->json($viewer, Response::HTTP_OK);
    }


}
