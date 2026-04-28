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

@if($careers->total() > 0)
    <div class="mt-8 flex items-center justify-between flex-wrap gap-4" id="pagination-wrapper">

        <!-- Range -->
        <div class="text-sm text-muted">
            {{ $careers->firstItem() }}-{{ $careers->lastItem() }} of {{ $careers->total() }}
        </div>

        <!-- Pagination -->
        <div class="flex items-center gap-2">

            <!-- Prev -->
            @if($careers->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-lightText cursor-not-allowed">
                    ‹
                </span>
            @else
                <a href="{{ $careers->previousPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                    ‹
                </a>
            @endif

            @php
                $current = $careers->currentPage();
                $last = $careers->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);

                if ($current <= 3) {
                    $end = min($last, 5);
                }

                if ($current >= $last - 2) {
                    $start = max(1, $last - 4);
                }
            @endphp

            <!-- Numbers -->
            @for($page = $start; $page <= $end; $page++)
                @if($page == $current)
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#7F64CE] text-white font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $careers->appends(request()->query())->url($page) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            <!-- Next -->
            @if($careers->hasMorePages())
                <a href="{{ $careers->nextPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                    ›
                </a>
            @else
                <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-lightText cursor-not-allowed">
                    ›
                </span>
            @endif

        </div>
    </div>
@endif
@endsection
@section('script')
<script  src="{{asset('js/admin.js')}}"></script>
@endsection