@extends('Admin.AdminLayout')

@section('content')

<!-- Header -->
<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-title">Careers</h1>
        <span class="text-sm text-muted">
            <span id="careers-count">{{ $careers->total() }}</span> Careers
        </span>
    </div>

    <a href="{{ route('Admin.careers.create') }}"
       class="px-5 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow-md hover:opacity-90">
        + Add Career
    </a>
</div>

<!-- Toolbar -->
<div class="flex gap-3 flex-wrap mb-5">

    <!-- Search -->
    <div class="flex items-center bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary flex-1 ">
        <input type="text" id="search" placeholder="Search careers…"
               class=" outline-none text-sm bg-transparent text-textMain placeholder:text-lightText">
    </div>

    <!-- Category -->
    <select id="category"
       class="bg-white border border-borderC rounded-lg px-4 py-2 text-sm text-muted shadow-sm
           focus:ring-2 focus:ring-primary flex-1">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

</div>



<!-- Grid -->
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8" id="careers-grid">
    @include('Admin.career.partials.cards', ['careers' => $careers])
</div>

@if($careers->hasPages())
    <div class="mt-8 flex justify-center" id="pagination-wrapper">
        {{ $careers->appends(request()->query())->links('pagination::tailwind') }}
    </div>

@endif
@endsection
@section('script')
<script  src="{{asset('js/admin.js')}}"></script>
@endsection