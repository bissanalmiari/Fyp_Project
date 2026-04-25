@php
    $favoriteProgramIds = $favoriteProgramIds ?? collect();
@endphp

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="text-lg font-semibold text-[#7F64CE]">
        {{ $programs->total() }} Programs
    </div>
</div>

<div class="space-y-6">
    @forelse($programs as $program)
        @php
            $isSaved = auth()->check() && $favoriteProgramIds->contains($program->id);
        @endphp

        <details class="group rounded-3xl bg-[#C3BFFA] p-6 shadow-md">
            <summary class="flex cursor-pointer list-none items-start justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-[#7F64CE]">{{ $program->name }}</h3>
                </div>

                <div class="flex shrink-0 items-center gap-4">
                    @auth
                        <button
                            type="button"
                            class="favorite-toggle flex items-center gap-2"
                            data-save-url="{{ route('program.favorite', $program->id) }}"
                            data-unsaved-icon="{{ asset('images/before_save_icon.png') }}"
                            data-saved-icon="{{ asset('images/after_save_icon.png') }}"
                        >
                            <img
                                src="{{ $isSaved ? asset('images/after_save_icon.png') : asset('images/before_save_icon.png') }}"
                                alt="Save program"
                                class="saveIcon h-4 w-4 cursor-pointer"
                            >
                            <span class="text-sm text-[#7F64CE]">favorite</span>
                        </button>
                    @endauth

                    <span class="program-chevron text-xl text-[#7F64CE] transition-transform">⌄</span>
                </div>
            </summary>

            <div class="mt-6 space-y-6">
                <div class="rounded-2xl p-2">
                    <h5 class="text-lg font-semibold tracking-wide text-[#5B527D]">General</h5>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Program Category</p>
                            <p class="font-semibold text-[#7F64CE]">{{ optional($program->category)->name ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Study Level</p>
                            <p class="font-semibold text-[#7F64CE]">{{ $program->level ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Study Mode</p>
                            <p class="font-semibold text-[#7F64CE]">{{ $program->study_mode ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Course Intensity</p>
                            <p class="font-semibold text-[#7F64CE]">{{ $program->course_intensity ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Languages</p>
                            <p class="font-semibold text-[#7F64CE]">
                                {{ $program->languages->pluck('name')->join(', ') ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Duration</p>
                            <p class="font-semibold text-[#7F64CE]">
                                {{ $program->duration ? ($program->duration <= 1 ? $program->duration . ' year' : $program->duration . ' years') : 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Website</p>
                            @if($program->url)
                                <a href="{{ $program->url }}" target="_blank" class="font-semibold text-[#7F64CE] underline">
                                    Open Program
                                </a>
                            @else
                                <p class="font-semibold text-[#7F64CE]">N/A</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl p-2">
                    <h5 class="text-lg font-semibold tracking-wide text-[#5B527D]">Tuition Fees</h5>

                    @php
                        $country = strtolower(trim($university->country));
                        $europeanCountries = ['germany', 'france', 'italy', 'spain'];

                        $localLabel = 'Local Students';
                        $localFee = null;
                        $otherLabel = 'Other Students';
                        $otherFee = null;

                        if ($country === 'lebanon') {
                            $localLabel = 'Lebanese Students';
                            $localFee = $program->leb_fees;
                            $otherFee = $program->eu_fees ?: $program->us_fees ?: $program->non_eu_fees ?: $program->arab_fees ?: $program->pal_fees;
                        } elseif (in_array($country, $europeanCountries)) {
                            $localLabel = 'EU Students';
                            $localFee = $program->eu_fees;
                            $otherLabel = 'Non-EU Students';
                            $otherFee = $program->non_eu_fees;
                        } elseif ($country === 'usa' || $country === 'united states' || $country === 'united states of america') {
                            $localLabel = 'USA Students';
                            $localFee = $program->us_fees;
                            $otherFee = $program->eu_fees ?: $program->non_eu_fees ?: $program->arab_fees ?: $program->leb_fees ?: $program->pal_fees;
                        } else {
                            $localFee = $program->eu_fees ?: $program->us_fees ?: $program->leb_fees;
                            $otherFee = $program->non_eu_fees ?: $program->arab_fees ?: $program->pal_fees;
                        }
                    @endphp

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">{{ $localLabel }}</p>
                            <p class="font-semibold text-[#7F64CE]">{{ $localFee ? $localFee . '/year' : 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">{{ $otherLabel }}</p>
                            <p class="font-semibold text-[#7F64CE]">{{ $otherFee ? $otherFee . '/year' : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $req = $program->requirement;
                @endphp

                <div class="rounded-2xl p-2">
                    <h5 class="text-lg font-semibold tracking-wide text-[#5B527D]">English Requirements</h5>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">SAT</p>
                            <p class="font-semibold text-[#7F64CE]">{{ optional($req)->sat ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">IELTS</p>
                            <p class="font-semibold text-[#7F64CE]">{{ optional($req)->ielts ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">TOEFL</p>
                            <p class="font-semibold text-[#7F64CE]">{{ optional($req)->toefl ?: 'N/A' }}</p>
                        </div>

                        <div class="rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-1 text-xs text-[#5B527D]">Minimum GPA</p>
                            <p class="font-semibold text-[#7F64CE]">{{ optional($req)->minimum_gpa ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </details>
    @empty
        <div class="rounded-3xl bg-[#C3BFFA] p-8 text-center font-semibold text-[#7F64CE] shadow-md">
            No programs found.
        </div>
    @endforelse
</div>

@if($programs->total() > 0)
    <div class="results-bottombar">
        <div class="results-range">
            {{ $programs->firstItem() }}-{{ $programs->lastItem() }} of {{ $programs->total() }}
        </div>

        <div class="custom-pagination">
            @if($programs->onFirstPage())
                <span class="page-arrow disabled">‹</span>
            @else
                <a href="#" class="page-arrow program-pagination-link" data-page="{{ $programs->currentPage() - 1 }}">‹</a>
            @endif

            @php
                $current = $programs->currentPage();
                $last = $programs->lastPage();

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
                    <span class="page-number active">{{ $page }}</span>
                @else
                    <a href="#" class="page-number program-pagination-link" data-page="{{ $page }}">{{ $page }}</a>
                @endif
            @endfor

            @if($programs->hasMorePages())
                <a href="#" class="page-arrow program-pagination-link" data-page="{{ $programs->currentPage() + 1 }}">›</a>
            @else
                <span class="page-arrow disabled">›</span>
            @endif
        </div>
    </div>
@endif