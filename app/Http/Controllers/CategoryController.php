<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController
{
    public function index()
    {
        return Category::all();
    }
}
