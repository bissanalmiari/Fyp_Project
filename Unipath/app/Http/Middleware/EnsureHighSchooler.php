<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHighSchooler
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $student = auth()->user()->student;

        if (! $student || $student->academic_level !== 'High School') {
            abort(403, 'This page is only available for high school students.');
        }

        return $next($request);
    }
}