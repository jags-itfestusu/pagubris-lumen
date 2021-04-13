<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $feeds = Feed::with(array('answers' => function ($query) {
            $query->withCount('answers')->latest()->take(3);
        }))->withCount(['answers'])
            ->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))->where('parent_feed_id', null)->latest()->get();

        return response()->json($feeds, 200, [], JSON_NUMERIC_CHECK);
    }

    public function indexAnswer($id)
    {
        $feed = Feed::with(['answers' => function ($query) {
            return $query->withCount('answers')->with(['answers' => function ($query) {
                return $query->latest()->take(3);
            }]);
        }])
            ->findOrFail($id);

        return $feed->answers;
    }

    public function get(Request $request, $id)
    {
        $feed = Feed::with(['answers' => function ($query) {
            return $query->with('answers')->withCount('answers');
        }])
            ->withCount('answers')
            ->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))
            ->findOrFail($id);
        return response()->json($feed, 200, [], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request, $id = null)
    {
        $this->validate($request, [
            'content' => 'required|string'
        ]);

        $feed = new Feed();
        $feed->id = Uuid::uuid6()->toString();
        $feed->owner_id = auth()->user()->id;
        $feed->parent_feed_id = $id;
        $feed = $this->save($request, $feed);
        $feed->creator = $feed->creator()->select(['id', 'name', 'avatar'])->first();
        return response($feed, 201);
    }

    public function storeAnswer(Request $request, $id)
    {
        Feed::findOrFail($id);

        return $this->store($request, $id);
    }

    private function save(Request $request, Feed $feed)
    {
        $feed->fill($request->only([
            'content'
        ]));
        $feed->save();
        return $feed;
    }
}
