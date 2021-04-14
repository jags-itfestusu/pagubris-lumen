<?php

namespace App\Http\Controllers;

use App\Models\User;

class HighlightController extends Controller
{
    public function index()
    {
        return User::inRandomOrder()->take(5)->get();
    }
}
