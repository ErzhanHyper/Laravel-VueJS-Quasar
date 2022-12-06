<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MailController;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationCategory;
use App\Models\ApplicationComment;
use App\Models\ApplicationFile;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function collect;
use function response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function all()
    {
        $application = Application::all();
        return ApplicationResource::collection($application)->response();
    }

    public function countNew(): JsonResponse
    {
        $user = auth()->user();
        $role_id = $user->role_id;
        if ($role_id === 1) {
            $application = Application::where('status_id', 1)->get();
        } else {
            $application_category = ApplicationCategory::where('role_id', $role_id)->get()[0];
            $application = Application::where('status_id', 1)->where('application_category_id', $application_category->id)->get();
        }


        return response()->json($application);
    }

    public function list()
    {
        $collection = collect();

        $employee_id = Employee::where('user_id', auth()->user()->id)->get()[0]->id;
        $application = Application::with('application_category')->orderByDesc('created_at');
        $user = User::find(Auth()->user()->id);
        if ($user->role->name === 'support' || $user->role->name === 'commercial') {
            $application = $application->whereRelation('application_category', 'role_id', '=', $user->role_id)->get();
            $current = Application::where('employee_id', $employee_id)->get();
            foreach ($current as $curr) {
                $collection->push($curr);
            }
            foreach ($application as $item) {
                $collection->push($item);
            }
        } else if ($user->role->name === 'admin') {
            $collection = $application;
        } else {
            $application->where('employee_id', $employee_id)->get();
            $collection = $application;
        }


        return ApplicationResource::collection($collection->paginate(10))->response();
    }

    public function get($id)
    {
        $application = Application::where('id', $id)->with('comments')->get();
        return ApplicationResource::collection($application)->response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create(Request $request)
    {
//        $application = new Application;
//        $application->text = $request->text;
//        $application->application_category_id = $request->category;
//        $application->employee_id = $request->employee;
//        $application->save();

        return response()->json($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'text' => 'required|string',
                'category' => 'required|integer',
            ],
            [
                'required' => 'Поля :attribute не может быть пустым.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'messages' => $validator->messages()
                ],
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $employee_id = Employee::where('user_id', auth()->user()->id)->first()->id;
        $application = new Application();
        $application->text = $request->text;
        $application->application_category_id = $request->category;
        $application->employee_id = $employee_id;
        $application->status_id = 1;
        if ($application->save()) {
            foreach ($request->file('files') as $key => $f) {
                $original_name = time() . '_' . $f->getClientOriginalName();
                $extension = $f->getClientOriginalExtension();
                $filename = config('app.url') . '/storage/uploads/application/' . $original_name;
                $f->move(public_path('storage/uploads/application'), $original_name);
                $file = new ApplicationFile;
                $file->application_id = $application->id;
                $file->file = $filename;
                $file->name = $original_name;
                $file->file_type = $extension;
                $file->save();
            }
        }

        try {
            $application_category = ApplicationCategory::find($request->category);
            $user = User::where('role_id', $application_category->role_id)->get();
            foreach ($user as $u) {
                $employee = Employee::where('user_id', $u->id)->get()[0];
                $mail = new MailController();
                $mail->application_create($employee->email);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => 'New application created successfully!',
                    'id' => $application->id
                ],
                Response::HTTP_CREATED
            );
        }

        return response()->json(
            [
                'success' => 'New application created successfully!',
                'id' => $application->id
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Applications $applications
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Applications $applications
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Applications $applications
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $application = Application::find($id);
        $application->status_id = $request->status;
        $application->save();

        return response()->json(
            [
                'success' => 'Application updated successfully!',
            ],
            Response::HTTP_OK
        );
    }

    public function comment(Request $request, $id): JsonResponse
    {
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $comment = new ApplicationComment();
        $comment->comment = $request->comment;
        $comment->employee_id = $employee_id;
        $comment->application_id = $id;
        $comment->save();

        return response()->json(
            [
                'success' => 'Application updated successfully!',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Applications $applications
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $application = Application::find($id);
        $application->delete($application->id);
        return response()->json(
            [
                'success' => 'Application deleted successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
