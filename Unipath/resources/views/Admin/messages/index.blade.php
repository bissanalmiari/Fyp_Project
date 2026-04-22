@extends('Admin.AdminLayout')

@section('content')

<div class="flex items-center justify-between flex-wrap gap-3 mb-7">
    <div>
        <h1 class="text-xl font-bold text-title">Messages</h1>
        <span class="text-sm text-muted">
            <span id="messages-count">{{ $messages->total() }}</span> Messages
        </span>
    </div>
</div>

<div class="flex gap-3 flex-wrap mb-5">
    <div class="flex items-center bg-white border border-borderC rounded-lg px-4 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary w-full max-w-md">
        <input
            type="text"
            id="message-search"
            placeholder="Search by name, email, or phone..."
            class="outline-none text-sm bg-transparent text-textMain placeholder:text-lightText w-full"
        >
    </div>
</div>

<p class="text-xs text-muted mb-4">
    Showing
    <span class="font-semibold text-title" id="messages-range">
        {{ $messages->firstItem() ?? 0 }}–{{ $messages->lastItem() ?? 0 }}
    </span>
    of <span class="font-semibold text-title" id="messages-count-bottom">{{ $messages->total() }}</span>
</p>

<div class="overflow-hidden rounded-xl border border-borderC bg-white shadow-sm mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="border-b border-borderC">
                <tr class="text-left text-sm text-title">
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Full Name</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Message</th>
                    <th class="px-6 py-4">Sent At</th>
                </tr>
            </thead>

            <tbody class="text-sm text-muted" id="messages-table-body">
                @include('Admin.messages.partials.table_rows', ['messages' => $messages])
            </tbody>
        </table>
    </div>
</div>

<div class="flex justify-center" id="messages-pagination-wrapper">
    @if($messages->hasPages())
        {{ $messages->links('pagination::simple-tailwind') }}
    @endif
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let messagesTimeout = null;

        function fetchMessages(url = null) {
            const searchEl = document.getElementById('message-search');
            if (!searchEl) return;

            const search = searchEl.value || '';
            let endpoint = url ?? window.location.pathname;

            const params = new URLSearchParams();
            if (search) params.append('search', search);

            fetch(`${endpoint}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tableBody = document.getElementById('messages-table-body');
                const pagination = document.getElementById('messages-pagination-wrapper');
                const countTop = document.getElementById('messages-count');
                const countBottom = document.getElementById('messages-count-bottom');
                const range = document.getElementById('messages-range');

                if (tableBody) tableBody.innerHTML = data.html;
                if (pagination) pagination.innerHTML = data.pagination;
                if (countTop) countTop.innerText = data.count;
                if (countBottom) countBottom.innerText = data.count;
                if (range) range.innerText = `${data.from ?? 0}–${data.to ?? 0}`;
            });
        }

        const messageSearchInput = document.getElementById('message-search');
        if (messageSearchInput) {
            messageSearchInput.addEventListener('input', function () {
                clearTimeout(messagesTimeout);
                messagesTimeout = setTimeout(fetchMessages, 300);
            });
        }

        document.addEventListener('click', function (e) {
            const link = e.target.closest('#messages-pagination-wrapper a');
            if (link) {
                e.preventDefault();
                fetchMessages(link.href);
            }
        });
    });
</script>
@endsection

