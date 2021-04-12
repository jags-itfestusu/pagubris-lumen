<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);
        return response()->json($user, 200, [], JSON_NUMERIC_CHECK);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $this->validate($request, [
            "email" => "required|string",
            "username" => "required|string",
            "name" => "required|string",
            "gender" => "required|in:MALE,FEMALE,OTHER",
            "bio" => "string",
        ]);

        if ($request->email != $user->email) {
            $emailCounts = User::where('email', $request->email)->count();
            if ($emailCounts > 0) {
                $this->validate($request, [
                    "email" => "required|string|unique:users",
                ]);
            }
        }

        if ($request->username != $user->username) {
            $usernameCounts = User::where('username', $request->username)->count();
            if ($usernameCounts > 0) {
                $this->validate($request, [
                    "username" => "required|string|unique:users",
                ]);
            }
        }

        $user->email = $request->email;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->bio = $request->has('bio') ? $request->bio : "";

        $user->save();

        return response()->json($user, 200, [], JSON_NUMERIC_CHECK);
    }
}
