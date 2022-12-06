<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $agent = Agent::all();
        return response()->json($agent);
    }

    public function store(Request $request): JsonResponse
    {
        $agent = new Agent();

        $agent->name = $request->name;
        $agent->bin = $request->bin;
        $agent->save();

        return response()->json($agent);
    }
}
