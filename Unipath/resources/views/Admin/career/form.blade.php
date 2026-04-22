@extends('Admin.AdminLayout')

@section('content')

<h2 class="text-xl font-bold text-title mb-6">
    {{ isset($career) ? 'Edit Career' : 'Add Career' }}
</h2>

<div class="bg-white border border-borderC rounded-xl p-7 shadow-sm">

    <p class="text-xs font-bold uppercase tracking-widest text-primary mb-5">
        Career Details
    </p>

    <form method="POST"
          action="{{ isset($career) ? route('Admin.careers.update', $career->id) : route('Admin.careers.store') }}"
          enctype="multipart/form-data">

        @csrf
        @if(isset($career)) @method('PUT') @endif

        <!-- Title -->
        <div class="mb-4">
            <label class="text-sm text-muted font-semibold">Title</label>
            <input type="text" name="title"
                   value="{{ old('title', $career->title ?? '') }}"
                   class="w-full mt-1 px-4 py-2 rounded-lg border border-borderC bg-bg focus:ring-2 focus:ring-primary outline-none">
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="text-sm text-muted font-semibold">Category</label>
            <select name="category_id"
                class="w-full mt-1 px-4 py-2 rounded-lg border border-borderC bg-bg focus:ring-2 focus:ring-primary">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $career->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="text-sm text-muted font-semibold">Description</label>
            <textarea name="description"
                class="w-full mt-1 px-4 py-2 rounded-lg border border-borderC bg-bg focus:ring-2 focus:ring-primary min-h-[100px]">
                {{ old('description', $career->description ?? '') }}
            </textarea>
        </div>

        <!-- Salary -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="text-sm text-muted font-semibold">Min Salary</label>
                <input type="number" name="min_salary"
                    value="{{ old('min_salary', $career->min_salary ?? '') }}"
                    class="w-full mt-1 px-4 py-2 rounded-lg border border-borderC bg-bg">
            </div>

            <div>
                <label class="text-sm text-muted font-semibold">Max Salary</label>
                <input type="number" name="max_salary"
                    value="{{ old('max_salary', $career->max_salary ?? '') }}"
                    class="w-full mt-1 px-4 py-2 rounded-lg border border-borderC bg-bg">
            </div>
        </div>

        <!-- Image -->
        <div class="mb-4">
            <label class="text-sm text-muted font-semibold">Career Image</label>
            <input type="file" name="image"
                   class="w-full mt-1 text-sm text-muted">
        </div>

        <!-- Checkboxes -->
        <div class="flex gap-6 mb-5">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active"
                    {{ old('is_active', $career->is_active ?? false) ? 'checked' : '' }}>
                Active
            </label>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="in_demand"
                    {{ old('in_demand', $career->in_demand ?? false) ? 'checked' : '' }}>
                In Demand
            </label>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full py-3 rounded-lg bg-primary text-white font-semibold shadow-md hover:bg-title transition">
            {{ isset($career) ? 'Update Career' : 'Create Career' }}
        </button>

    </form>

</div>

@endsection