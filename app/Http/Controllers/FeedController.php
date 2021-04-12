<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        return Feed::with(array('creator' => function ($query) {
            $query->select('id', 'name', 'avatar');
        }))->latest()->get();
    }

    public function get(Request $request, $id)
    {
        $feed = Feed::with(array('creator' => function ($query) {
            $query->select('id', 'name', 'avatar');
        }))->findOrFail($id);
        return response()->json($feed, 200, [], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string'
        ]);

        $feed = new Feed();
        $feed->id = Uuid::uuid6()->toString();
        $feed->owner_id = auth()->user()->id;
        $feed = $this->save($request, $feed);
        $feed->creator = $feed->creator()->select(['id', 'name', 'avatar'])->first();
        return response($feed, 201);
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
