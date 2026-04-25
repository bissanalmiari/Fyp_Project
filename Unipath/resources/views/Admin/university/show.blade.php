@extends('Admin.AdminLayout')

@section('content')

<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-xl font-bold text-title">University Details</h1>
        <p class="text-sm text-muted mt-1">{{ $university->name }}</p>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('Admin.universities.edit', $university->id) }}"
           class="rounded-lg bg-title px-5 py-3 text-sm font-semibold text-white">
            Edit
        </a>

        <a href="{{ route('Admin.universities') }}"
           class="rounded-lg border border-borderC bg-white px-5 py-3 text-sm font-semibold text-title">
            Back
        </a>
    </div>
</div>

<div class="rounded-2xl bg-white border border-borderC shadow-sm p-6">
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full lg:w-1/3">
            <div class="rounded-2xl overflow-hidden bg-bg border border-borderC h-[240px] flex items-center justify-center">
                @if($university->image)
                    <img src="{{ $university->image }}" alt="{{ $university->name }}" class="h-full w-full object-cover">
                @else
                    <span class="text-sm text-muted">No Image</span>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-2/3 space-y-5">
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 rounded-2xl overflow-hidden bg-bg border border-borderC flex items-center justify-center">
                    @if($university->logo)
                        <img src="{{ $university->logo }}" alt="{{ $university->name }} logo" class="h-full w-full object-contain p-2">
                    @else
                        <span class="text-xs text-muted">Logo</span>
                    @endif
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-title">{{ $university->name }}</h2>
                    <p class="text-sm text-muted mt-1">{{ $university->city ?? 'N/A' }}, {{ $university->country ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl bg-bg p-4">
                    <p class="text-xs text-muted">Type</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $university->type ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-4">
                    <p class="text-xs text-muted">Rank</p>
                    <p class="mt-1 font-semibold text-textMain">{{ $university->rank ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-4 md:col-span-2">
                    <p class="text-xs text-muted">Website</p>
                    @if($university->website_url)
                        <a href="{{ $university->website_url }}" target="_blank" class="mt-1 block break-all font-semibold text-title underline">
                            {{ $university->website_url }}
                        </a>
                    @else
                        <p class="mt-1 font-semibold text-textMain">N/A</p>
                    @endif
                </div>

                <div class="rounded-xl bg-bg p-4">
                    <p class="text-xs text-muted">Instagram</p>
                    <p class="mt-1 break-all font-semibold text-textMain">{{ $university->insta ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-4">
                    <p class="text-xs text-muted">LinkedIn</p>
                    <p class="mt-1 break-all font-semibold text-textMain">{{ $university->linkedin ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-4 md:col-span-2">
                    <p class="text-xs text-muted">Facebook</p>
                    <p class="mt-1 break-all font-semibold text-textMain">{{ $university->facebook ?? 'N/A' }}</p>
                </div>

                <div class="rounded-xl bg-bg p-4 md:col-span-2">
                    <p class="text-xs text-muted">Description</p>
                    <p class="mt-2 text-sm leading-7 text-textMain">{{ $university->description ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection