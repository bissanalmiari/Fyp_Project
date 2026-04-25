<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;

class HomeController extends Controller
{
    public function index()
    {
        $stories = SuccessStory::where('is_published', true)
            ->latest()
            ->get();

        return view('index', compact('stories'));
    }
}