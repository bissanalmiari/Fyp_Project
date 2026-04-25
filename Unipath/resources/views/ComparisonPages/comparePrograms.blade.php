<x-app-layout>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/comparison.css') }}">
    @endpush

    <div class="comparison-page min-h-screen bg-[#F6F4FE]">

        <section class="hero">
            <div class="hero-content">
                <div class="hero-img">
                    <img src="{{ asset('images/comparison_graphic.png') }}" alt="Comparison Illustration">
                </div>

                <div class="hero-text">
                    <span class="hero-label">OUR PLATFORM</span>
                    <div class="hero-title">
                        <h1>Program Comparison</h1>
                        <p>Choose Smarter</p>
                    </div>

                    <p class="hero-subtitle">
                        Select two universities, then choose one program from each to prepare them for comparison.
                    </p>

                    <div class="hero-btns">
                        <a href="#comparison-section" class="btn-primary">Get Started</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="comparison-section" class="mx-auto max-w-7xl px-6 py-14 lg:px-12">
            <div class="mb-10 rounded-3xl border border-[#7F64CE]/15 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-4xl font-bold text-[#7F64CE]">About This Comparison Tool</h2>
                        <p class="mt-3 max-w-3xl text-[#5B527D]">
                            Compare two programs based on academic fit, tuition compatibility, personal preferences, and relevance to the student’s selected categories, sub categories, and major.
                        </p>
                    </div>

                    <button
                        type="button"
                        id="about-toggle-btn"
                        class="rounded-2xl border border-[#7F64CE] bg-white px-6 py-3 font-semibold text-[#7F64CE] transition hover:bg-[#C498F2] hover:text-white"
                    >
                        See More
                    </button>
                </div>

                <div id="about-content" class="mt-6 hidden">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="rounded-2xl bg-[#F6F4FE] p-5">
                            <h4 class="mb-3 text-lg font-bold text-[#7F64CE]">How to Use</h4>
                            <ul class="space-y-2 text-sm text-[#5B527D]">
                                <li>1. Select University A and University B.</li>
                                <li>2. Select one program from each university.</li>
                                <li>3. Press Compare Programs.</li>
                                <li>4. Review the detailed points table and compatibility indicator.</li>
                                <li>5. Export the result as PDF if needed.</li>
                            </ul>
                        </div>

                        <div class="rounded-2xl bg-[#F6F4FE] p-5">
                            <h4 class="mb-3 text-lg font-bold text-[#7F64CE]">Scoring Criteria</h4>
                            <ul class="space-y-2 text-sm text-[#5B527D]">
                                <li><strong>Academic:</strong> level, GPA, SAT, English requirements</li>
                                <li><strong>Cost:</strong> tuition compared to the selected budget range</li>
                                <li><strong>Preferences:</strong> location, study mode, course intensity</li>
                                <li><strong>Relevance:</strong> category match, sub category match, and major relevance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @auth
                <form
                    action="{{ route('comparison.index') }}"
                    method="GET"
                    id="comparison-filter-form"
                    data-endpoint="{{ route('comparison.index') }}"
                    class="space-y-10"
                >
                    <input type="hidden" name="university_a_id" id="university_a_id" value="{{ $selectedUniversityAId }}">
                    <input type="hidden" name="university_b_id" id="university_b_id" value="{{ $selectedUniversityBId }}">
                    <input type="hidden" name="program_a_id" id="program_a_id" value="{{ $selectedProgramAId }}">
                    <input type="hidden" name="program_b_id" id="program_b_id" value="{{ $selectedProgramBId }}">

                    <div>
                        <h3 class="mb-6 text-3xl font-bold text-[#7F64CE]">Select Universities</h3>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="compare-dropdown" data-target-input="university_a_id" data-placeholder="Select University A">
                                <button type="button" class="dropdown-trigger flex w-full items-center justify-between rounded-2xl border border-[#7F64CE] bg-white px-5 py-4 text-left text-[#5B527D] shadow-sm transition hover:border-[#C498F2]">
                                    <span class="selected-label">{{ $selectedUniversityA?->name ?? 'Select University A' }}</span>
                                    <span>⌄</span>
                                </button>

                                <div class="dropdown-panel mt-3 hidden rounded-2xl border border-[#CDDBFD] bg-white p-4 shadow-lg">
                                    <div class="relative mb-4">
                                        <input type="text" class="dropdown-search w-full rounded-xl border border-[#CDDBFD] px-4 py-3 pr-10 text-[#5B527D] outline-none focus:border-[#7F64CE]" placeholder="Search university...">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7F64CE]">⌕</span>
                                    </div>

                                    <div class="dropdown-options max-h-72 space-y-2 overflow-y-auto pr-1">
                                        @foreach($universities as $university)
                                            <label class="dropdown-option flex min-h-[78px] cursor-pointer items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#F6F4FE]">
                                                <input
                                                    type="radio"
                                                    name="draft_university_a"
                                                    value="{{ $university->id }}"
                                                    data-label="{{ $university->name }}"
                                                    class="mt-1"
                                                    {{ (string)$selectedUniversityAId === (string)$university->id ? 'checked' : '' }}
                                                >
                                                <div>
                                                    <p class="font-medium text-[#5B527D]">{{ $university->name }}</p>
                                                    <p class="mt-1 text-xs text-[#7F64CE]/80">
                                                        {{ $university->country ?: 'No country' }}
                                                        @if($university->city)
                                                            • {{ $university->city }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-5 flex items-center justify-between gap-3">
                                        <button type="button" class="dropdown-reset w-full rounded-xl border border-[#CDDBFD] bg-white px-4 py-3 font-semibold text-[#7F64CE]">
                                            Reset
                                        </button>
                                        <button type="button" class="dropdown-apply w-full rounded-xl bg-[#C498F2] px-4 py-3 font-semibold text-white">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="compare-dropdown" data-target-input="university_b_id" data-placeholder="Select University B">
                                <button type="button" class="dropdown-trigger flex w-full items-center justify-between rounded-2xl border border-[#7F64CE] bg-white px-5 py-4 text-left text-[#5B527D] shadow-sm transition hover:border-[#C498F2]">
                                    <span class="selected-label">{{ $selectedUniversityB?->name ?? 'Select University B' }}</span>
                                    <span>⌄</span>
                                </button>

                                <div class="dropdown-panel mt-3 hidden rounded-2xl border border-[#CDDBFD] bg-white p-4 shadow-lg">
                                    <div class="relative mb-4">
                                        <input type="text" class="dropdown-search w-full rounded-xl border border-[#CDDBFD] px-4 py-3 pr-10 text-[#5B527D] outline-none focus:border-[#7F64CE]" placeholder="Search university...">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[#7F64CE]">⌕</span>
                                    </div>

                                    <div class="dropdown-options max-h-72 space-y-2 overflow-y-auto pr-1">
                                        @foreach($universities as $university)
                                            <label class="dropdown-option flex min-h-[78px] cursor-pointer items-center gap-3 rounded-xl px-4 py-3 hover:bg-[#F6F4FE]">
                                                <input
                                                    type="radio"
                                                    name="draft_university_b"
                                                    value="{{ $university->id }}"
                                                    data-label="{{ $university->name }}"
                                                    class="mt-1"
                                                    {{ (string)$selectedUniversityBId === (string)$university->id ? 'checked' : '' }}
                                                >
                                                <div>
                                                    <p class="font-medium text-[#5B527D]">{{ $university->name }}</p>
                                                    <p class="mt-1 text-xs text-[#7F64CE]/80">
                                                        {{ $university->country ?: 'No country' }}
                                                        @if($university->city)
                                                            • {{ $university->city }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-5 flex items-center justify-between gap-3">
                                        <button type="button" class="dropdown-reset w-full rounded-xl border border-[#CDDBFD] bg-white px-4 py-3 font-semibold text-[#7F64CE]">
                                            Reset
                                        </button>
                                        <button type="button" class="dropdown-apply w-full rounded-xl bg-[#C498F2] px-4 py-3 font-semibold text-white">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="program-selectors-container">
                        @include('ComparisonPages.partials.programSelectors')
                    </div>
                </form>
            @else
                <div class="space-y-10">
                    <div>
                        <h3 class="mb-6 text-3xl font-bold text-[#7F64CE]">Select Universities</h3>

                        <div class="rounded-3xl border border-[#7F64CE]/15 bg-white p-8 text-center shadow-sm">
                            <p class="text-[#5B527D]">
                                Login to use the comparison tool.
                            </p>

                            <a
                                href="{{ route('login') }}"
                                class="mt-6 inline-flex items-center justify-center rounded-2xl bg-[#C498F2] px-8 py-3 font-semibold text-white transition hover:opacity-90"
                            >
                                Login to Use the Comparison Tool
                            </a>
                        </div>
                    </div>
                </div>
            @endauth

            <div id="selected-program-cards-container">
                @include('ComparisonPages.partials.selectedProgramCards', [
                    'comparisonData' => $comparisonData,
                ])
            </div>

            <div
                id="comparison-section-container"
                data-compare-endpoint="{{ route('comparison.compare') }}"
                data-export-endpoint="{{ route('comparison.export') }}"
            >
                <div id="comparison-section-inner">
                    @include('ComparisonPages.partials.comparisonSection', [
                        'selectedProgramA' => $selectedProgramA,
                        'selectedProgramB' => $selectedProgramB,
                        'criteriaBlueprint' => $criteriaBlueprint,
                        'comparisonData' => $comparisonData,
                        'isCompared' => $isCompared,
                    ])
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/comparison.js') }}"></script>
    @endpush

</x-app-layout>