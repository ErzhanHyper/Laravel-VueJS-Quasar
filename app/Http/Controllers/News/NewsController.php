<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\News;
use App\Models\NewsViewer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function config;
use function public_path;
use function request;
use function response;
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function all()
    {
        $news = News::orderBy('created_at','desc')->get();
        return NewsResource::collection($news)->response();
    }

    public function list()
    {
        $news = News::where('publish', 1)->get();
        return NewsResource::collection($news)->response();
    }

    public function get($id)
    {
        $news = News::find($id);
        return response()->json(new NewsResource($news));
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'text' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }

        $filename = '';
        if(request('file')) {
            $f = request('file');
            $original_name = time() . '_' . $f->getClientOriginalName();
            $filename = 'news/' . $original_name;
            $f->move(public_path('storage/uploads/news'), $original_name);
        }


        if($request->publish === 'true'){
            $publish = 1;
        }else{
            $publish = 0;
        }

        if($request->chat === 'true'){
            $chat = 1;
        }else{
            $chat = 0;
        }

        if($request->imgFullWidth === 'true'){
            $imgFullWidth = 1;
        }else{
            $imgFullWidth = 0;
        }

        $news = News::find($id);

        $news->publish = $publish;
        $news->chat = $chat;
        $news->img_full_width = $imgFullWidth;
        $news->title = $request->title;
        $news->text = $request->text;
        if($filename !== '') {
            $news->image = $filename;
        }
        $news->save();

        return response()->json($news);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'text' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->messages(),
                Response::HTTP_EXPECTATION_FAILED
            );
        }


        $filename = '';
        if(request('file')) {
            $f = request('file');
            $original_name = time() . '_' . $f->getClientOriginalName();
            $filename = 'news/' . $original_name;
            $f->move(public_path('storage/uploads/news'), $original_name);
        }

        $image_filename = '';
        if(request('image')) {
            $f = request('image');
            $original_name = time() . '_' . $f->getClientOriginalName();
            $image_filename = 'news/' . $original_name;
            $f->move(public_path('storage/uploads/news'), $original_name);
        }

        if($request->publish === 'true'){
            $publish = 1;
        }else{
            $publish = 0;
        }

        if($request->chat === 'true'){
            $chat = 1;
        }else{
            $chat = 0;
        }

        if($request->imgFullWidth === 'true'){
            $imgFullWidth = 1;
        }else{
            $imgFullWidth = 0;
        }

        $news = new News();
        $news->title = $request->title;
        $news->text = $request->text;
        $news->publish = $publish;
        $news->chat = $chat;
        $news->img_full_width = $imgFullWidth;
        $news->image = $image_filename;
        $news->file = $filename;

        $news->save();

        return response()->json($news);
    }

    public function storeViewer(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
        $viewer = NewsViewer::where('employee_id', $employee_id)->where('news_id', $request->id);
        $viewed = false;
        if($viewer->count() >0 ){
            $viewed = true;
        }

        if(!$viewed) {
            $viewer = new NewsViewer;
            $viewer->news_id = $request->id;
            $viewer->employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
            $viewer->save();
            $result = ['created successfully!'];
        }else{
            $result = ['also exist!'];
        }

        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\news  $news
     * @return \Illuminate\Http\Response
     */
    public function show(news $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\news  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(news $news)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\news  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(news $news)
    {
        //
    }
}
