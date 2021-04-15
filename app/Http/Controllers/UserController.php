<?php

namespace App\Http\Controllers;

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
}
