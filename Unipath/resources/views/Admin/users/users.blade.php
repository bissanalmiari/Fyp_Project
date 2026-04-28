@extends('Admin.AdminLayout')

@section('content')

<!-- Header -->
<div class="flex justify-between items-center flex-wrap gap-3 mb-6">

    <h1 class="text-xl font-bold text-title">Users</h1>

    <!-- Search Box -->
    <div class="flex items-center gap-2 bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary transition">

        <input
            type="text"
            id="searchInput"
           
            placeholder="Search by Name"
            class="bg-transparent outline-none text-sm text-textMain placeholder:text-lightText w-[200px] md:w-full "
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

<!-- Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="users-grid">
    @include('Admin.users.partials.cards', ['students' => $students])
</div>

<!-- Pagination -->
<!-- Pagination -->
@if($students->total() > 0)
    <div class="mt-8 flex items-center justify-between flex-wrap gap-4" id="pagination-wrapper">

        <div class="text-sm text-muted">
            {{ $students->firstItem() }}-{{ $students->lastItem() }} of {{ $students->total() }}
        </div>

        <div class="flex items-center gap-2">
            @if($students->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-lightText cursor-not-allowed">
                    ‹
                </span>
            @else
                <a href="{{ $students->previousPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                    ‹
                </a>
            @endif

            @php
                $current = $students->currentPage();
                $last = $students->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);

                if ($current <= 3) {
                    $end = min($last, 5);
                }

                if ($current >= $last - 2) {
                    $start = max(1, $last - 4);
                }
            @endphp

            @for($page = $start; $page <= $end; $page++)
                @if($page == $current)
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#7F64CE] text-white font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $students->url($page) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($students->hasMorePages())
                <a href="{{ $students->nextPageUrl() }}"
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
<script src="{{ asset('js/admin.js') }}"></script>
@endsection