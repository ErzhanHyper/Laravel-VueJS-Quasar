<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use function response;

class UserController extends Controller
{
    public function get($id){
        $user = User::find($id);
        return response()->json($user);
    }
}
