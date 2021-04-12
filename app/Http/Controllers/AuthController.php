<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);
        if (!$token = auth()->setTTL(1440)->attempt($credentials))
            throw new AuthenticationException();

        return $this->respondAccess($token);
    }

    public function refresh()
    {
        return $this->respondAccess(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();
        return response(['message' => 'Logged out']);
    }

    public function me()
    {
        return response($this->getUser());
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'gender' => 'required|in:MALE,FEMALE,OTHER',
            'password' => 'required|string|confirmed'
        ]);

        $inputs = $request->only([
            'email',
            'username',
            'name',
            'gender',
            'password',
        ]);

        $user = new User();
        $user->id = Uuid::uuid6()->toString();
        $user->fill($inputs);
        $user->password = Hash::make($inputs['password']);
        $user->avatar = null;
        $user->bio = "";
        $user->save();

        $credentials = [
            'email' => $user->email,
            'password' => $inputs['password'],
        ];

        if (!$token = auth()->setTTL(1440)->attempt($credentials))
            throw new AuthenticationException();

        return $this->respondAccess($token);
    }

    private function getUser()
    {
        $user = auth()->user();
        return [
            'username' => $user->username,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];
    }

    public function respondAccess($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $this->getUser(),
        ]);
    }
}
