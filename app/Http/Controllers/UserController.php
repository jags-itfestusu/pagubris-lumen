<?php

namespace App\Http\Controllers;

use App\Models\FollowingUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|string|min:3'
        ]);
        $users = User::select(['id', 'name', 'avatar'])->where('name', 'LIKE', '%' . $request->q . '%')->orWhere('username', 'LIKE', '%' . $request->q . '%')->get();
        foreach ($users as $key => $user) {
            if ($user->id == auth()->user()->id)
                unset($users[$key]);
        }
        return $users;
    }

    public function get($id)
    {
        $user = User::select(['id', 'name', 'avatar', 'gender', 'bio'])->withCount('followers')->withCount('following')->findOrFail($id);
        return response()->json($user, 200, [], JSON_NUMERIC_CHECK);
    }

    public function follow($id)
    {
        User::findOrFail($id);

        $checkFollowing = FollowingUser::where('self_id', auth()->user()->id)->where('following_id', $id)->first();
        if ($checkFollowing == null) {
            $following = new FollowingUser();
            $following->self_id = auth()->user()->id;
            $following->following_id = $id;
            $following->save();
        }
        return response([
            'message' => 'User has been followed'
        ]);
    }

    public function unfollow($id)
    {
        User::findOrFail($id);

        $checkFollowing = FollowingUser::where('self_id', auth()->user()->id)->where('following_id', $id)->first();
        if ($checkFollowing != null)
            $checkFollowing->delete();
        return response([
            'message' => 'User has been unfollowed'
        ]);
    }
}
