@extends('Admin.AdminLayout')

@section('content')

<div class="flex justify-between items-center flex-wrap gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-title">Programs</h1>
        <p class="text-sm text-muted mt-1">
            <span id="programs-range">{{ $programs->firstItem() ?? 0 }}–{{ $programs->lastItem() ?? 0 }}</span>
            of
            <span id="programs-count">{{ $programs->total() }}</span>
        </p>
    </div>

    <div class="flex items-center gap-2 bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary transition">
        <input
            type="text"
            id="program-search"
            value="{{ request('search') }}"
            placeholder="Search by Program Name"
            class="bg-transparent outline-none text-sm text-textMain placeholder:text-lightText w-[220px] md:w-full"
        >

        <svg class="w-4 h-4 text-muted flex-shrink-0"
             xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
</div>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
<div class="flex items-center gap-3 mb-4">
    <span class="text-sm font-medium text-textMain">Filter by:</span>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

    <select id="program-university"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">University</option>
        @foreach($universities as $university)
            <option value="{{ $university->id }}" {{ request('university_id') == $university->id ? 'selected' : '' }}>
                {{ $university->name }}
            </option>
        @endforeach
    </select>

    <select id="program-category"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select id="program-subcategory"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">Sub Category</option>
        @foreach($subcategories as $subcategory)
            <option value="{{ $subcategory->id }}"
                data-category="{{ $subcategory->category_id }}"
                {{ request('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                {{ $subcategory->name }}
            </option>
        @endforeach
    </select>

    <select id="program-level"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">Level</option>
        @foreach($levels as $level)
            <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                {{ $level }}
            </option>
        @endforeach
    </select>

    <select id="program-study-mode"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">Study Mode</option>
        @foreach($studyModes as $mode)
            <option value="{{ $mode }}" {{ request('study_mode') == $mode ? 'selected' : '' }}>
                {{ $mode }}
            </option>
        @endforeach
    </select>

    <select id="program-course-intensity"
        class="w-full rounded-xl border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
        <option value="">Course Intensity</option>
        @foreach($courseIntensities as $intensity)
            <option value="{{ $intensity }}" {{ request('course_intensity') == $intensity ? 'selected' : '' }}>
                {{ $intensity }}
            </option>
        @endforeach
    </select>

</div>

    <a href="{{ route('Admin.programs.create') }}"
       class="inline-flex items-center justify-center rounded-lg bg-title px-5 py-3 text-sm font-semibold text-white hover:opacity-90">
        Add Program
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6" id="programs-grid">
    @include('Admin.program.partials.cards', ['programs' => $programs])
</div>

@if($programs->hasPages())
    <div class="mt-8 flex justify-center" id="programs-pagination">
        {{ $programs->links('pagination::tailwind') }}
    </div>
@endif

@endsection

@section('script')
<script src="{{ asset('js/admin.js') }}"></script>
@endsection