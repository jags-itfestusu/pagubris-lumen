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
            $query->select('id', 'first_name', 'last_name', 'avatar');
        }))->latest()->get();
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
        $feed->creator = $feed->creator()->select(['id', 'first_name', 'last_name', 'avatar'])->first();
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
