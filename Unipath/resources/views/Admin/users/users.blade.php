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
@if($students->hasPages())
<div class="mt-8 flex justify-center" id="pagination-wrapper">
    {{ $students->links('pagination::tailwind') }}
</div>
@endif

@endsection
@section('script')
<script src="{{ asset('js/admin.js') }}"></script>
@endsection