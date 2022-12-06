<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $quotes = Quote::orderBy('created_at', 'desc')->get();
        return response()->json($quotes);
    }


    public function get($id): \Illuminate\Http\JsonResponse
    {
        $quote = Quote::find($id);
        return response()->json($quote);
    }

    public function getLast(): \Illuminate\Http\JsonResponse
    {
        $quotes = Quote::whereDate("created_at","=",date("Y-m-d",time() ) )->orderBy('created_at', 'desc')->first();

        $result = [];
        if($quotes){
            $result = $quotes;
        }
        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'text' => 'required|string',
            ],
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $quote = new Quote;
        $quote->text = $request->text;
        $quote->save();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $quote = Quote::find($id);
        $quote->text = $request->text;
        $quote->save();

        return response()->json($quote);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quote = Quote::find($id);
        $quote->delete($quote->id);
    }
}
