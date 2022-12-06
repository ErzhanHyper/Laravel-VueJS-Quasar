<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use function bcrypt;
use function config;
use function response;
use function storage_path;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function birthday(Request $request)
    {
        $from = strtotime(Carbon::now()->startOfMonth());
        $to = strtotime(Carbon::now()->endOfMonth());
        $employee = Employee::orderBy('birthdate', 'asc')->whereBetween('birthdate', [$from, $to])->get();
        return EmployeeResource::collection($employee)->response();
    }

    public function getName()
    {
        $employee = Employee::select('id', 'lastname', 'firstname', 'middlename')->orderBy('lastname', 'asc')->get();
        return EmployeeResource::collection($employee)->response();
    }


    public function all(Request $request)
    {

        if($request->filter){
            $employee = Employee::select('id', 'lastname', 'firstname', 'middlename')->orderBy('lastname', 'asc')->get();
            return response()->json($employee);
        }
        $employee = Employee::orderBy('lastname', 'asc')->get();
        return EmployeeResource::collection($employee)->response();
    }

    public function list(Request $request)
    {
        $employee = Employee::orderBy('lastname', 'asc');

        if ($request->employee_id) {
            $employee->where('id', $request->employee_id);
        }
        if ($request->department_id) {
            $employee->where('department_id', $request->department_id);
        }
        if ($request->profession_id) {
            $employee->where('profession_id', $request->profession_id);
        }
        if ($request->status_id) {
            $employee->where('employee_status_id', $request->status_id);
        }
        return EmployeeResource::collection($employee->paginate(10))->response();

    }

    public function get($id)
    {
        $employee = Employee::where('id', $id)->get();
        return EmployeeResource::collection($employee)->response();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = User::where('email', $request->email);
        if ($user->count() > 0) {
            return response()->json(
                [
                    'messages' => [['Пользователь уже существует!']]
                ],
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $validator = Validator::make($request->all(),
            [
                'lastname' => 'required|string',
                'firstname' => 'required|string',
                'status' => 'required|integer',
                'profession' => 'required|integer',
                'department' => 'required|integer',

                'login' => 'required|string',
                'password' => 'required|string',
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
        $photo_url = '';
        if ($request['photo']) {
            $photo = $request['photo'];
            $image = Image::make($photo);
            $mime = $image->mime();
            $extension = explode('/', $mime)[1];
            $path = "employee/photo/";
            $photo_name = "avatar_" . substr(time(), 5) . "." . $extension;
            $image->save(storage_path('app/public/uploads/' . $path . $photo_name));
            $photo_url = 'employee/photo/' . $photo_name;
        }
        $input['password'] = bcrypt($request->password);
        $user = new User;
        $user->name = $request->login;
        $user->email = $request->email;
        $user->password = $input['password'];
        $user->role_id = 3;
        if ($user->save()) {
            $employee = new Employee;
            $employee->lastname = $request->lastname;
            $employee->firstname = $request->firstname;
            $employee->middlename = $request->middlename;
            $employee->extension = $request->extension;
            $employee->cabinet = $request->cabinet;
            $employee->profession_id = $request->profession;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->department_id = $request->department;
            $employee->employee_status_id = $request->status;
            $employee->birthdate = strtotime($request->birthdate);
            $employee->user_id = $user->id;
            $employee->photo = $photo_url;
            $employee->text = $request->text;
            $employee->save();
        }

        return response()->json(
            [
                'success' => 'New employee created successfully!',
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if ($request->firstname) {
            $employee->firstname = $request->firstname;
        }
        if ($request->lastname) {
            $employee->lastname = $request->lastname;
        }
        if ($request->middlename) {
            $employee->middlename = $request->middlename;
        }
        if ($request->text) {
            $employee->text = $request->text;
        }

        $employee->phone = $request->phone;
        $employee->email = $request->email;

        $employee->extension = $request->ext;
        $employee->cabinet = $request->cabinet;

        if ($request->employee_status_id) {
            $employee->employee_status_id = $request->employee_status_id;
        }
        if ($request->profession_id) {
            $employee->profession_id = $request->profession_id;
        }
        if ($request->department_id) {
            $employee->department_id = $request->department_id;
        }

        if ($request->birthdate) {
            $employee->birthdate = strtotime($request->birthdate);
        }

        if ($request->photo) {
            $photo = $request->photo;
            $image = Image::make($photo);
            $mime = $image->mime();
            $extension = explode('/', $mime)[1];
            $path = "employee/photo/";
            $photo_name = "avatar_" . substr(time(), 5) . "." . $extension;
            $image->save(storage_path('app/public/uploads/' . $path . $photo_name));
            $photo_url = 'employee/photo/' . $photo_name;
            $employee->photo = $photo_url;
        }

        $employee->save();

        return response()->json(
            [
                'success' => 'employee updated successfully!',
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $user = User::find($employee->user_id);
        $user->delete($user->id);
        $employee->delete($employee->id);

        return response()->json(
            [
                'success' => 'employee delete successfully!',
            ],
            Response::HTTP_OK
        );
    }
}
