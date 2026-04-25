@extends('Admin.AdminLayout')

@section('content')

<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-title">Quiz</h1>
        <span class="text-sm text-muted">
            {{ $questions->flatten()->count() }} Questions
        </span>
    </div>
</div>

@php
    $trackLabels = [
        'core' => 'Core Questions',
        'tech' => 'Technology Track',
        'engineering' => 'Engineering Track',
        'business' => 'Business Track',
        'health' => 'Health Track',
        'creative' => 'Creative Track',
        'science' => 'Science Track',
        'social' => 'Social Track',
    ];
@endphp

<div class="space-y-8">
    @forelse($questions as $track => $trackQuestions)
        <div>
            <div class="mb-4">
                <h2 class="text-lg font-bold text-title">
                    {{ $trackLabels[$track] ?? ucfirst(str_replace('_', ' ', $track)) }}
                </h2>
                <p class="text-sm text-muted">
                    {{ $trackQuestions->count() }} questions
                </p>
            </div>

            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
                @include('Admin.quiz.partials.cards', ['trackQuestions' => $trackQuestions])
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-16">
            No quiz questions found.
        </div>
    @endforelse
</div>

@endsection