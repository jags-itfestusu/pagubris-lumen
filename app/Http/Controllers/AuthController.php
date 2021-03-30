<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

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

    private function getUser()
    {
        $user = auth()->user();
        return [
            'email' => $user->email,
            'name' => $user->name,
        ];
    }

    public function respondAccess($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
