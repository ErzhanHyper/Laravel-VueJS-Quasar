<?php

namespace App\Services\ProjectService;

use App\Http\Controllers\MailController;
use App\Http\Resources\ProjectResource;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectEmployee;
use App\Models\ProjectFile;
use App\Services\File\FileStore;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Void_;

class ProjectService
{

    public FileStore $file_store_service;

    public function __construct(FileStore $file_store_service)
    {
        $this->file_store_service = $file_store_service;
    }

    public function list($query): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;

        if ($employee_id !== 1) {
            $project_employee = ProjectEmployee::where('employee_id', $employee_id)->first();
            if ($project_employee) {
                $project = Project::with(['employee', 'employees', 'files', 'status'])->where('id', $project_employee->project_id)->orderByDesc('created_at')->paginate(10);
            }
        } else {
            $project = Project::with(['employee', 'employees', 'files', 'status'])->orderByDesc('created_at')->paginate(10);
        }
        return response()->json(ProjectResource::collection($project), Response::HTTP_OK);
    }

    public function get($query, $id): JsonResponse
    {
        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;

        if ($employee_id !== 1) {
            $project_employee = ProjectEmployee::where('employee_id', $employee_id)->first();
            if ($project_employee) {
                $project = Project::find($project_employee->project_id);
            }else{
                return response()->json(['message' => 'not_access'], Response::HTTP_EXPECTATION_FAILED);
            }
        } else {
            $project = Project::find($id);
        }

        $project->with(['employee', 'employees', 'files', 'status']);

        return response()->json(new ProjectResource($project), Response::HTTP_OK);
    }

    public function store($query): JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'name' => 'required|max:512',
                'employees' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $employee_ids = explode(',', $query->employees);
        $files = $query->file('files');

        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $project = new Project();
        $project->name = $query->name;
        $project->description = $query->description;
        $project->employee_id = $employee_id;
        $project->status_id = 1;

        if ($project->save()) {
            if (count($employee_ids) > 0) {

                foreach ($employee_ids as $id){
                    $params = $query->merge([
                        'employee_id' => $id,
                        'project_id' => $project->id
                    ]);
                    $this->inviteProjectEmployee($params);
                }
                $this->storeProjectEmployee($project->id, $employee_ids);
            }

            if ($files) {
                $this->storeProjectFile($project->id, $files);
            }
        }
        return response()->json($query);
    }

    public function update($query, $id): JsonResponse
    {

        $validator = Validator::make($query->all(),
            [
                'name' => 'required|max:512',
                'employees' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $employee_ids = explode(',', $query->employees);
        $files = $query->file('files');

        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $project = Project::find($id);
        $project->name = $query->name;
        $project->description = $query->description;
        $project->employee_id = $employee_id;
        $project->status_id = 1;

        if ($project->save()) {
            if (count($employee_ids) > 0) {
                $this->storeProjectEmployee($project->id, $employee_ids);
            }

            if ($files) {
                $this->storeProjectFile($project->id, $files);
            }
        }
        return response()->json($query);
    }

    public function storeProjectEmployee($project_id, $employee_ids)
    {

        if (count($employee_ids) === 0) {
            return response()->json(
                ['message' => ['Не выбран сотрудник из списка']],
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $employee_find_list = [];
        for ($i = 0; $i < count($employee_ids); $i++) {
            $employee_find = ProjectEmployee::where('employee_id', $employee_ids[$i])->where('project_id', $project_id)->first();
            if (!$employee_find) {
                $project_employee = new ProjectEmployee();
                $project_employee->project_id = $project_id;
                $project_employee->employee_id = $employee_ids[$i];
                $project_employee->save();
                $request = new Request();
                $params =  $request->merge([
                    'project_id' => $project_id,
                    'employee_id' =>  $employee_ids[$i]
                ]);
                $this->inviteProjectEmployee($params);
            } else {
                $employee_find_list[] = Employee::find($employee_find->employee_id);
                $employee_find_list[] = 'Уже добавлены';
            }
        }

        return response()->json(['message' => $employee_find_list], Response::HTTP_OK);
    }

    public function delete($id): JsonResponse
    {
        $project = Project::find($id);

        $project_files = ProjectFile::where('project_id', $project->id);
        foreach ($project_files->get() as $file){
            if (Storage::exists('public/uploads/' . $file->file)) {
                Storage::delete('public/uploads/' . $file->file);
                $project_files->delete();
            }
        }
        $project->delete();
        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function storeProjectFile($project_id, $files)
    {
        foreach ($files as $file) {
            $filename = $this->file_store_service->store($file, 'project');
            $project_employee = new ProjectFile();
            $project_employee->project_id = $project_id;
            $project_employee->name = str_replace('/', '', strstr($filename, '/'));
            $project_employee->file = $filename;
            $project_employee->save();
        }
    }


    public function inviteProjectEmployee($query): JsonResponse
    {
        $project = Project::find($query->project_id);

        $title = 'Рабочий проект';
        $body = "Вас приглашают в рабочий проект \n". "Наименование: ".$project->name. "\n".  $project->description ."\n". 'Ссылка: http://192.168.0.20/project/'.$project->id.'/show' ;

        $email = Employee::find($query->employee_id)->email;
        $mail = new MailController();
        $mail->event_create($email, $title, $body);
        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

    public function removeProjectEmployee($query)
    {
        $project_employee = ProjectEmployee::where('employee_id', $query->employee_id)->delete();
        return response()->json(['message' => 'success'], Response::HTTP_OK);
    }

}
