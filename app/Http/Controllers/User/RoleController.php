<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use function response;

class RoleController extends Controller
{
    public function get($id): \Illuminate\Http\JsonResponse
    {
        $role = Role::with(['permission', 'user'])->find($id);
        return response()->json($role);
    }

    public function getRoleEmployee($id): \Illuminate\Http\JsonResponse
    {
        $role = Role::with(['permission', 'user'])->find($id);
        $user_ids = [];
        $employee = [];
        if ($role->user->count() > 0) {
            foreach ($role->user as $item) {
                $user_ids[] = $item->id;
            }
            $employee = Employee::whereIn('user_id', $user_ids)->select('id', 'user_id', 'lastname', 'firstname')->get();
        }

        return response()->json($employee);
    }

    public function list(Request $request): \Illuminate\Http\JsonResponse
    {

        $id = $request->get('id');
        $page = $request->get('page');

        $role = Role::orderByDesc('name')->with('permission');

        if ($id) {
            $role->where('id', $id);
        }

        return response()->json($role->get());
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $role = Role::find($id)->with('permission');

        return response()->json($role);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $permissions = $request->permissions;
        $employee_ids = $request->employees;
        $role = Role::find($id);
        $role->name = $request->name;
        $role->status = $request->active;
        if ($role->save()) {

            if (count($employee_ids) > 0) {
                foreach ($employee_ids as $item) {
                    $employee = Employee::find($item);
                    $user = User::find($employee->user_id);
                    $user->role_id = $role->id;
                    $user->save();
                }
            }

            if (count($permissions) > 0) {
                foreach ($permissions as $item) {
                    Permission::where('role_id', $role->id)->where('section', $item['section'])->delete();
                    $permission = new Permission;
                    $permission->role_id = $role->id;
                    $permission->access = json_encode($item['permission']);
                    $permission->section = $item['section'];
                    $permission->save();
                }
            }
        }
        return response()->json($request->active);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $employee_ids = $request->employees;
        $permissions = $request->permissions;
        $role = new Role;
        $role->name = $request->name;
        $role->description = $request->description;
        $role->status = $request->active;
        if ($role->save()) {

            if (count($employee_ids) > 0) {
                foreach ($employee_ids as $item) {
                    $employee = Employee::find($item);
                    $user = User::find($employee->user_id);
                    $user->role_id = $role->id;
                    $user->save();
                }
            }

            if (count($permissions) > 0) {
                foreach ($permissions as $item) {
                    $permission = new Permission;
                    $permission->role_id = $role->id;
                    $permission->access = json_encode($item['permission']);
                    $permission->section = $item['section'];
                    $permission->save();
                }
            }
        }
        return response()->json($permissions);
    }
}
