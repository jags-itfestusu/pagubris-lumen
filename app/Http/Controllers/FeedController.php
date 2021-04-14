<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $feeds = Feed::where('parent_feed_id', null)
            ->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))->withCount('answers')->latest()->get();
        foreach ($feeds as $feed) {
            $feed->answers = $feed->answers()->withCount('answers')->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))->latest()->take(3)->get();
        }
        return response()->json($feeds, 200, [], JSON_NUMERIC_CHECK);
    }

    public function indexAnswer($id)
    {
        $feed = Feed::with(['answers' => function ($query) {
            return $query->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))->withCount('answers');
        }])->findOrFail($id);

        $answers = $feed->answers;
        foreach ($answers as $answer) {
            $answer->answers = $answer->answers()->with(array('creator' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }))->latest()->take(3)->get();
        }
        return $answers;
    }

    public function get($id)
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

    public function store(Request $request, $parentFeed = null)
    {
        $this->validate($request, [
            'content' => 'required|string',
        ]);

        if ($parentFeed == null) {
            $this->validate($request, [
                'category_id' => 'required|string',
            ]);
        }

        $feed = new Feed();
        $feed->id = Uuid::uuid6()->toString();
        $feed->owner_id = auth()->user()->id;
        if ($parentFeed == null) {
            $feed->category_id = $request->category_id;
            $feed->parent_feed_id = null;
        } else {
            $feed->category_id = $parentFeed->category_id;
            $feed->parent_feed_id = $parentFeed->id;
        }
        $feed = $this->save($request, $feed);
        $feed->creator = $feed->creator()->select(['id', 'name', 'avatar'])->first();
        return response($feed, 201);
    }

    public function storeAnswer(Request $request, $id)
    {
        $parentFeed = Feed::findOrFail($id);

        return $this->store($request, $parentFeed);
    }

    public function update(Request $request, $id)
    {
        $feed = Feed::findOrFail($id);
        if ($feed->owner_id !== auth()->user()->id) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => "You can't change other user feed."
            ], 403);
        }
        $this->validate($request, [
            'content' => 'required|string',
        ]);
        $feed = $this->save($request, $feed);
        $feed->creator = $feed->creator()->select(['id', 'name', 'avatar'])->first();
        return response()->json($feed, 200, [], JSON_NUMERIC_CHECK);
    }

    public function destroy($id)
    {
        $feed = Feed::findOrFail($id);
        if ($feed->owner_id !== auth()->user()->id) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => "You can't delete other user feed."
            ], 403);
        }
        $feed->delete();
        return response(null, 204);
    }

    private function save(Request $request, Feed $feed)
    {
        $feed->fill($request->all());
        $feed->save();
        return $feed;
    }
}
