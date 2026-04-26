<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        $stories = SuccessStory::where('status', 'approved')
            ->latest()
            ->get();

        $popularPrograms = Program::with('university')
            ->withCount('favorites')
            ->orderByDesc('favorites_count')
            ->take(3)
            ->get();

        return view('index', compact('stories', 'popularPrograms'));
    }
}