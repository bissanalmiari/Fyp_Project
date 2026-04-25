@extends('Admin.AdminLayout')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-title">{{ $program->name }}</h1>
        <p class="text-sm text-muted mt-1">{{ optional($program->university)->name ?? 'N/A' }}</p>
    </div>

    <div class="bg-white border border-borderC rounded-2xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Category</p>
                <p class="mt-1 text-base font-semibold text-title">{{ optional($program->category)->name ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Sub Category</p>
                <p class="mt-1 text-base font-semibold text-title">{{ optional($program->subcategory)->name ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Level</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->level ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Duration</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->duration ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Study Mode</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->study_mode ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Course Intensity</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->course_intensity ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4 md:col-span-2 xl:col-span-3">
                <p class="text-sm text-muted">Languages</p>
                <p class="mt-1 text-base font-semibold text-title">
                    {{ $program->languages->pluck('name')->join(', ') ?: 'N/A' }}
                </p>
            </div>

            <div class="rounded-xl bg-bg p-4 md:col-span-2 xl:col-span-3">
                <p class="text-sm text-muted">Requirements</p>
                <p class="mt-2 text-base text-textMain">
                    SAT: {{ optional($program->requirement)->sat ?? 'N/A' }} |
                    IELTS: {{ optional($program->requirement)->ielts ?? 'N/A' }} |
                    TOEFL: {{ optional($program->requirement)->toefl ?? 'N/A' }} |
                    GPA: {{ optional($program->requirement)->minimum_gpa ?? 'N/A' }}
                </p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">EU Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->eu_fees ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Non-EU Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->non_eu_fees ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Arab Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->arab_fees ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Lebanese Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->leb_fees ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">Palestinian Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->pal_fees ?? 'N/A' }}</p>
            </div>

            <div class="rounded-xl bg-bg p-4">
                <p class="text-sm text-muted">US Fees</p>
                <p class="mt-1 text-base font-semibold text-title">{{ $program->us_fees ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mt-6">
            <a href="{{ route('Admin.programs.edit', $program->id) }}"
               class="rounded-lg bg-title px-6 py-3 text-sm font-semibold text-white">
                Edit
            </a>

            <a href="{{ route('Admin.programs') }}"
               class="rounded-lg border border-borderC bg-white px-6 py-3 text-sm font-semibold text-title">
                Back
            </a>
        </div>
    </div>
</div>
@endsection