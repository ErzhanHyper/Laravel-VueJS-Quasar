<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\FeedResource;
use App\Models\Employee;
use App\Models\Feed;
use App\Models\FeedCategory;
use App\Models\News;
use App\Models\NewsViewer;
use App\Services\FeedService\FeedChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use function collect;
use function response;

class FeedController extends Controller
{

    private FeedChatService $service;

    public function __construct(FeedChatService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $feed = Feed::find($id);
        return response()->json(new FeedResource($feed));
    }

    public function list(Request $request)
    {
        $feeds = Feed::orderByDesc('created_at')->paginate(10);
        return FeedResource::collection($feeds)->response();
    }

    public function category(): \Illuminate\Http\JsonResponse
    {
        return response()->json(FeedCategory::all());
    }

    public function all(Request $request): \Illuminate\Http\JsonResponse
    {
        $collection = collect();

        $feeds = Feed::orderBy('created_at', 'DESC')->get();
        $news = News::orderBy('created_at', 'DESC')->get();

        foreach ($feeds as $item) {
            $type = 'feed';
            $employee = Employee::where('id', $item->employee_id)->first();
            $photo = '';
            if($employee){
                $photo = $employee->photo;
            }
            if ($item->feed_category->id === 2 || $item->feed_category->id === 3) {
                $type = 'chat';
            }
            $item = [
                'id' => $item->id,
                'title' => $item->feed_category->name,
                'text' => $item->text,
                'image' => $photo,
                'type' => $type,
                'date' => $item->created_at,
                'viewed' => false,
                'viewers' => []
            ];
            $collection->push($item);
        }

        foreach ($news as $item) {
            if ($item->publish === 1) {
                $employee_id = Employee::where('user_id', Auth()->user()->id)->get()[0]->id;
                $viewer = NewsViewer::where('employee_id', $employee_id)->where('news_id', $item->id);
                $viewed = false;
                if($viewer->count() >0 ){
                    $viewed = true;
                }
                $employees = [];
                $viewers = NewsViewer::where('news_id', $item->id)->get();
                foreach ($viewers as $viewer) {
                    $employees[] = new EmployeeResource(Employee::find($viewer->employee_id));
                }
                $item = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'text' => $item->text,
                    'image' => $item->image,
                    'type' => 'news',
                    'date' =>$item->created_at,
                    'viewed' => $viewed,
                    'viewers' => $employees
                ];
                $collection->push($item);
            }

        }

        $sorted = $collection->sortByDesc(function ($card) {
            return $card['date'];
        });

        $data = (new Collection($sorted))->paginate(100);
        return response()->json($data);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee_id = $request->employee_id;
        $text = $request->text;

        $feed = new Feed();
        $feed->feed_category_id = $request->category_id;
        $feed->text = $text;
        if($employee_id) {
            $feed->employee_id = $employee_id;
        }
        $feed->user = Auth()->user()->id;

        $feed->save();

        return response()->json($feed);
    }

    public function update(Request $request, $id)
    {
        $employee_id = $request->employee_id;
        $text = $request->text;

        $feed = Feed::find($id);
        $feed->feed_category_id = $request->category_id;
        $feed->text = $text;
        $feed->employee_id = $employee_id;
        $feed->user = Auth()->user()->id;

        $feed->save();

        return response()->json($feed);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function edit(Feed $feed)
    {
        //
    }



    public function sendMessage(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->send($request);
    }

    public function deleteMessage($id): \Illuminate\Http\JsonResponse
    {
        return $this->service->delete($id);
    }

    public function getMessage(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->get($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Feed $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feed = Feed::find($id);
        $feed->delete();
    }
}
