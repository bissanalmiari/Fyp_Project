@extends('Admin.AdminLayout')

@section('content')

<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-title">Success Stories</h1>
        <span class="text-sm text-muted">
            <span>{{ $stories->total() }}</span> Stories
        </span>
    </div>
</div>

<div class="flex gap-3 flex-wrap mb-5">
    <div class="flex items-center bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm w-full max-w-md">
        <input
            type="text"
            id="stories-search"
            placeholder="Search by name, email, or phone..."
            class="outline-none text-sm bg-transparent text-textMain placeholder:text-lightText w-full"
        >
    </div>

    <select id="stories-status"
        class="bg-white border border-borderC rounded-lg px-4 py-2 text-sm text-muted shadow-sm">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="disapproved">Disapproved</option>
    </select>
</div>

<p class="text-xs text-muted mb-4">
    Showing
    <span class="font-semibold text-title">
        {{ $stories->firstItem() ?? 0 }}–{{ $stories->lastItem() ?? 0 }}
    </span>
    of <span class="font-semibold text-title">{{ $stories->total() }}</span>
</p>

<div id="stories-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    @include('Admin.success-stories.partials.cards', ['stories' => $stories])
</div>

@if($stories->total() > 0)
    <div class="mt-8 flex items-center justify-between flex-wrap gap-4" id="pagination-wrapper">

        <div class="text-sm text-muted">
            {{ $stories->firstItem() }}-{{ $stories->lastItem() }} of {{ $stories->total() }}
        </div>

        <div class="flex items-center gap-2">
            @if($stories->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-lightText cursor-not-allowed">
                    ‹
                </span>
            @else
                <a href="{{ $stories->previousPageUrl() }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                    ‹
                </a>
            @endif

            @php
                $current = $stories->currentPage();
                $last = $stories->lastPage();

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
                    <a href="{{ $stories->url($page) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg border border-borderC text-textMain hover:bg-[#F6F4FE] hover:text-[#7F64CE] transition">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($stories->hasMorePages())
                <a href="{{ $stories->nextPageUrl() }}"
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let storiesTimeout = null;

        function fetchStories(url = null) {
            const searchEl = document.getElementById('stories-search');
            const statusEl = document.getElementById('stories-status');

            if (!searchEl) return;

            const search = searchEl.value || '';
            const status = statusEl ? statusEl.value : '';
            let endpoint = url ?? window.location.pathname;

            const params = new URLSearchParams();

            if (search) params.append('search', search);
            if (status) params.append('status', status);

            fetch(`${endpoint}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('stories-grid');
                const pagination = document.getElementById('stories-pagination-wrapper');

                if (grid) grid.innerHTML = data.html;
                if (pagination) pagination.innerHTML = data.pagination;
            })
            .catch(error => {
                console.error('Success stories search error:', error);
            });
        }

        const searchInput = document.getElementById('stories-search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(storiesTimeout);
                storiesTimeout = setTimeout(fetchStories, 300);
            });
        }

        const statusSelect = document.getElementById('stories-status');
        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                fetchStories();
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#stories-pagination-wrapper a');
            if (link) {
                e.preventDefault();
                fetchStories(link.href);
            }
        });
    });
</script>

@endsection