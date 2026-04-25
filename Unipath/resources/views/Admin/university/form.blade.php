@extends('Admin.AdminLayout')

@section('content')
@php
    $isEdit = isset($university);
@endphp

<div class="mb-6">
    <h1 class="text-xl font-bold text-title">
        {{ $isEdit ? 'Edit University' : 'Add University' }}
    </h1>
    <p class="text-sm text-muted mt-1">Fill in the university details below.</p>
</div>

<div class="rounded-2xl bg-white border border-borderC shadow-sm p-6">
    <form
        action="{{ $isEdit ? route('Admin.universities.update', $university->id) : route('Admin.universities.store') }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $university->name ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Type</label>
                <select
                    name="type"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                    <option value="">Select type</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ old('type', $university->type ?? '') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Country</label>
                <input
                    type="text"
                    name="country"
                    value="{{ old('country', $university->country ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('country')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">City</label>
                <input
                    type="text"
                    name="city"
                    value="{{ old('city', $university->city ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('city')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Rank</label>
                <input
                    type="number"
                    name="rank"
                    value="{{ old('rank', $university->rank ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('rank')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Website URL</label>
                <input
                    type="url"
                    name="website_url"
                    value="{{ old('website_url', $university->website_url ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('website_url')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Instagram URL</label>
                <input
                    type="url"
                    name="insta"
                    value="{{ old('insta', $university->insta ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('insta')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">LinkedIn URL</label>
                <input
                    type="url"
                    name="linkedin"
                    value="{{ old('linkedin', $university->linkedin ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('linkedin')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-title">Facebook URL</label>
                <input
                    type="url"
                    name="facebook"
                    value="{{ old('facebook', $university->facebook ?? '') }}"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('facebook')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Logo URL</label>
                <input
                    type="url"
                    name="logo"
                    value="{{ old('logo', $university->logo ?? '') }}"
                    placeholder="https://example.com/logo.png"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('logo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-title">Main Image URL</label>
                <input
                    type="url"
                    name="image"
                    value="{{ old('image', $university->image ?? '') }}"
                    placeholder="https://example.com/image.jpg"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-title">Backup Image URL</label>
                <input
                    type="url"
                    name="backup_image"
                    value="{{ old('backup_image', $university->backup_image ?? '') }}"
                    placeholder="https://example.com/backup-image.jpg"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >
                @error('backup_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-title">Description</label>
                <textarea
                    name="description"
                    rows="5"
                    class="w-full rounded-lg border border-borderC bg-white px-4 py-3 outline-none focus:border-title"
                >{{ old('description', $university->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-title px-6 py-3 font-semibold text-white">
                {{ $isEdit ? 'Update University' : 'Create University' }}
            </button>

            <a href="{{ route('Admin.universities') }}"
               class="rounded-lg border border-borderC bg-white px-6 py-3 font-semibold text-title">
                Back
            </a>
        </div>
    </form>
</div>
@endsection