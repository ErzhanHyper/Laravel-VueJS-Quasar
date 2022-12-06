<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function getUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::id())->with('role')->get();
        $userCollection = UserResource::collection($user);

        $employee = Employee::where('user_id', Auth::id())->get();
        $employeeCollection = EmployeeResource::collection($employee);

        $result = [
            'user' => $userCollection,
            'employee' => $employeeCollection
        ];

        return response()->json($result);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'user logged out'
        ];
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'required' => 'Поля :attribute не может быть пустым.'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = User::where('name', $request->username)->with(['role'])->first();
        if(!$user){
            return response()->json(['error' => [['Пользователь с таким логином не существует!']]], 401);
        }
        $employee = Employee::where('user_id', $user->id)->get();
        $employee_res = EmployeeResource::collection($employee);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => [['Данные не верны!']]], 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'employee' => $employee_res[0],
            'user' => $user,
            'token' => $token
        ];

        return response()->json($res);

    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = new User;
        $user->name = $input['username'];
        $user->email = 'email@mail.ru';
        $user->password = $input['password'];
        $user->save();

        $token = $user->createToken('apiToken')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = User::where('name', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }
        return response()->json(['token' => $user->createToken('apiToken')->plainTextToken]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()], \Illuminate\Http\Response::HTTP_EXPECTATION_FAILED);
        }

        $user = User::find($id);
        $name = $request->name;

        if($name){
            $user->name = $name;
            $user->save();
        }

        return response()->json($user);
    }


    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()], \Illuminate\Http\Response::HTTP_EXPECTATION_FAILED);
        }

        $password = bcrypt($request->password);

        $user = User::find($id);
        $user->password = $password;
        $user->password_changed = true;
        $user->save();

        return response()->json($user);
    }

}
