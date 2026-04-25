@extends('Admin.AdminLayout')

@section('content')

<div class="flex justify-between items-center flex-wrap gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-title">Universities</h1>
        <p class="text-sm text-muted mt-1">
            <span id="universities-range">{{ $universities->firstItem() ?? 0 }}–{{ $universities->lastItem() ?? 0 }}</span>
            of
            <span id="universities-count">{{ $universities->total() }}</span>
        </p>
    </div>

    <div class="flex items-center gap-2 bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary transition">
        <input
            type="text"
            id="university-search"
            placeholder="Search by Name"
            class="bg-transparent outline-none text-sm text-textMain placeholder:text-lightText w-[200px] md:w-full"
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
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm font-medium text-textMain">Filter by:</span>

        <select id="university-country" class="rounded-lg border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
            <option value="">Country</option>
            @foreach($countries as $country)
                <option value="{{ $country }}">{{ $country }}</option>
            @endforeach
        </select>

        <select id="university-city" class="rounded-lg border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
            <option value="">City</option>
            @foreach($cities as $city)
                <option value="{{ $city }}">{{ $city }}</option>
            @endforeach
        </select>

        <select id="university-type" class="rounded-lg border border-borderC bg-white px-4 py-2 text-sm text-textMain outline-none">
            <option value="">Type</option>
            @foreach($types as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <a href="{{ route('Admin.universities.create') }}"
       class="inline-flex items-center justify-center rounded-lg bg-title px-5 py-3 text-sm font-semibold text-white hover:opacity-90">
        Add University
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="universities-grid">
    @include('Admin.university.partials.cards', ['universities' => $universities])
</div>

@if($universities->hasPages())
    <div class="mt-8 flex justify-center" id="universities-pagination">
        {{ $universities->links('pagination::tailwind') }}
    </div>
@endif

@endsection

@section('script')
<script src="{{ asset('js/admin.js') }}"></script>
@endsection