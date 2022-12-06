<?php

namespace App\Http\Controllers;

use App\Models\NPALink;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class NPALinkController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'link' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $npa = new NPALink();
        $npa->name = $request->name;
        $npa->link = $request->link;

        $npa->save();

        return response()->json($npa, Response::HTTP_OK);
    }

    public function list()
    {
        $npa = NPALink::all();
        return response()->json($npa);
    }

    public function delete($id)
    {
        $npa = NPALink::find($id);
        $npa->delete($npa->id);
        return response()->json($npa, Response::HTTP_OK);
    }
}
