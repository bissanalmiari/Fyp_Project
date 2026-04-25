@extends('Admin.AdminLayout')

@section('content')
@php
    $isEdit = isset($program);

    $selectedLanguageIds = collect(
        old(
            'language_ids',
            isset($program) ? $program->languages->pluck('id')->toArray() : []
        )
    )->map(fn ($id) => (string) $id)->all();
@endphp

<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-title">
            {{ $isEdit ? 'Edit Program' : 'Add Program' }}
        </h1>
        <p class="text-sm text-muted mt-1">Fill in the program details below.</p>
    </div>

    <form
        action="{{ $isEdit ? route('Admin.programs.update', $program->id) : route('Admin.programs.store') }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="bg-white border border-borderC rounded-2xl shadow-sm p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Program Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $program->name ?? '') }}"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">University</label>
                    <select
                        name="university_id"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select university</option>
                        @foreach($universities as $university)
                            <option value="{{ $university->id }}" {{ old('university_id', $program->university_id ?? '') == $university->id ? 'selected' : '' }}>
                                {{ $university->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('university_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Category</label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $program->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Sub Category</label>
                    <select
                        id="subcategory_id"
                        name="subcategory_id"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select sub category</option>
                        @foreach($subcategories as $subcategory)
                            <option
                                value="{{ $subcategory->id }}"
                                data-category="{{ $subcategory->category_id }}"
                                {{ old('subcategory_id', $program->subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}
                            >
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Level</label>
                    <select
                        name="level"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}" {{ old('level', $program->level ?? '') == $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                    @error('level')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Study Mode</label>
                    <select
                        name="study_mode"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select study mode</option>
                        @foreach($studyModes as $mode)
                            <option value="{{ $mode }}" {{ old('study_mode', $program->study_mode ?? '') == $mode ? 'selected' : '' }}>
                                {{ $mode }}
                            </option>
                        @endforeach
                    </select>
                    @error('study_mode')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Course Intensity</label>
                    <select
                        name="course_intensity"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                        <option value="">Select intensity</option>
                        @foreach($courseIntensities as $intensity)
                            <option value="{{ $intensity }}" {{ old('course_intensity', $program->course_intensity ?? '') == $intensity ? 'selected' : '' }}>
                                {{ $intensity }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_intensity')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Duration</label>
                    <input
                        type="text"
                        name="duration"
                        value="{{ old('duration', $program->duration ?? '') }}"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                    @error('duration')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-title">Program URL</label>
                    <input
                        type="url"
                        name="url"
                        value="{{ old('url', $program->url ?? '') }}"
                        class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title"
                    >
                    @error('url')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white border border-borderC rounded-2xl shadow-sm p-6">
            <h2 class="mb-4 text-lg font-bold text-title">Fees</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">EU Fees</label>
                    <input type="number" step="0.01" name="eu_fees" value="{{ old('eu_fees', $program->eu_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Non-EU Fees</label>
                    <input type="number" step="0.01" name="non_eu_fees" value="{{ old('non_eu_fees', $program->non_eu_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Arab Fees</label>
                    <input type="number" step="0.01" name="arab_fees" value="{{ old('arab_fees', $program->arab_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Lebanese Fees</label>
                    <input type="number" step="0.01" name="leb_fees" value="{{ old('leb_fees', $program->leb_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Palestinian Fees</label>
                    <input type="number" step="0.01" name="pal_fees" value="{{ old('pal_fees', $program->pal_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">US Fees</label>
                    <input type="number" step="0.01" name="us_fees" value="{{ old('us_fees', $program->us_fees ?? '') }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>
            </div>
        </div>

        <div class="bg-white border border-borderC rounded-2xl shadow-sm p-6">
            <h2 class="mb-4 text-lg font-bold text-title">Program Requirements</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">SAT</label>
                    <input type="number" name="sat" value="{{ old('sat', optional($program->requirement ?? null)->sat) }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">IELTS</label>
                    <input type="number" step="0.1" name="ielts" value="{{ old('ielts', optional($program->requirement ?? null)->ielts) }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">TOEFL</label>
                    <input type="number" name="toefl" value="{{ old('toefl', optional($program->requirement ?? null)->toefl) }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-title">Minimum GPA</label>
                    <input type="number" step="0.1" name="minimum_gpa" value="{{ old('minimum_gpa', optional($program->requirement ?? null)->minimum_gpa) }}"
                           class="w-full rounded-xl border border-borderC px-4 py-3 outline-none focus:border-title">
                </div>
            </div>
        </div>

        <div class="bg-white border border-borderC rounded-2xl shadow-sm p-6">
            <h2 class="mb-4 text-lg font-bold text-title">Languages</h2>

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($languages as $language)
                    <label class="flex items-center gap-3 rounded-xl border border-borderC bg-bg px-4 py-3">
                        <input
                            type="checkbox"
                            name="language_ids[]"
                            value="{{ $language->id }}"
                            {{ in_array((string) $language->id, $selectedLanguageIds, true) ? 'checked' : '' }}
                        >
                        <span class="text-sm font-medium text-textMain">{{ $language->name }}</span>
                    </label>
                @endforeach
            </div>

            @error('language_ids')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror

            @error('language_ids.*')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-title px-6 py-3 text-sm font-semibold text-white">
                {{ $isEdit ? 'Update Program' : 'Create Program' }}
            </button>

            <a href="{{ route('Admin.programs') }}"
               class="rounded-lg border border-borderC bg-white px-6 py-3 text-sm font-semibold text-title">
                Back
            </a>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    if (!categorySelect || !subcategorySelect) return;

    function filterSubcategories() {
        const selectedCategoryId = categorySelect.value;
        const currentSubcategoryValue = subcategorySelect.value;

        Array.from(subcategorySelect.options).forEach(option => {
            if (option.value === '') {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.category !== selectedCategoryId;
        });

        subcategorySelect.disabled = !selectedCategoryId;

        if (currentSubcategoryValue) {
            const selectedOption = Array.from(subcategorySelect.options).find(
                option => option.value === currentSubcategoryValue
            );

            if (!selectedOption || selectedOption.dataset.category !== selectedCategoryId) {
                subcategorySelect.value = '';
            }
        }
    }

    categorySelect.addEventListener('change', filterSubcategories);
    filterSubcategories();
});
</script>
@endsection