@php
    $favoriteProgramIds = $favoriteProgramIds ?? collect();
    $programCount = method_exists($programs, 'total') ? $programs->total() : $programs->count();
@endphp

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-[#E6E0F8] bg-white px-4 py-2 text-sm font-semibold text-[#7F64CE] shadow-sm">
        <span class="h-2 w-2 rounded-full bg-[#C498F2]"></span>
        {{ $programCount }} Programs
    </div>
</div>

<div class="space-y-5">
    @forelse($programs as $program)
        @php
            $isSaved = auth()->check() && $favoriteProgramIds->contains($program->id);
            $languagesText = $program->languages && $program->languages->count()
                ? $program->languages->pluck('name')->join(', ')
                : 'N/A';
        @endphp

        <details class="group overflow-hidden rounded-[28px] border border-[#E6E0F8] bg-white shadow-[0_14px_35px_rgba(127,100,206,0.10)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_20px_45px_rgba(127,100,206,0.16)] open:translate-y-0 open:shadow-[0_22px_55px_rgba(127,100,206,0.18)]">

            <summary id="program-{{ $program->id }}"
                data-name="{{ $program->name }}"
                class="flex cursor-pointer list-none items-center justify-between gap-5 px-7 py-6 transition group-open:border-b group-open:border-[#E6E0F8] group-open:bg-[#FBFAFF]">

                <div class="flex min-w-0 items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#F1EDFF] text-lg font-bold text-[#7F64CE]">
                        {{ $loop->iteration }}
                    </div>

                    <div class="min-w-0">
                        <h3 class="text-xl font-extrabold text-[#7F64CE] md:text-2xl">
                            {{ $program->name }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $program->level ?: 'N/A' }}

                            @if($program->study_mode)
                                • {{ $program->study_mode }}
                            @endif

                            @if($program->course_intensity)
                                • {{ $program->course_intensity }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-4">
                    @auth
                        <button
                            type="button"
                            class="favorite-toggle flex items-center gap-2 rounded-full border border-[#E6E0F8] bg-white px-3 py-2 text-sm font-semibold text-[#7F64CE] transition hover:bg-[#F6F4FE]"
                            data-save-url="{{ route('program.favorite', $program->id) }}"
                            data-unsaved-icon="{{ asset('images/before_save_icon.png') }}"
                            data-saved-icon="{{ asset('images/after_save_icon.png') }}"
                        >
                            <img
                                src="{{ $isSaved ? asset('images/after_save_icon.png') : asset('images/before_save_icon.png') }}"
                                alt="Save program"
                                class="saveIcon h-4 w-4 cursor-pointer"
                            >
                            <span class="hidden sm:inline">Favorite</span>
                        </button>
                    @endauth

                    <span class="program-chevron flex h-10 w-10 items-center justify-center rounded-full bg-[#F6F4FE] text-[#7F64CE] transition duration-300 group-open:rotate-180 group-open:bg-[#7F64CE] group-open:text-white">
                        ˅
                    </span>
                </div>
            </summary>

            <div class="bg-gradient-to-br from-white to-[#F8F6FF] px-7 py-7">

                {{-- General --}}
                <div class="mb-8">
                    <h5 class="mb-4 text-lg font-bold text-[#7F64CE]">General</h5>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Program Category</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ optional($program->category)->name ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Study Level</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $program->level ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Study Mode</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $program->study_mode ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Course Intensity</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $program->course_intensity ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Languages</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $languagesText }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $program->duration ? ($program->duration <= 1 ? $program->duration . ' year' : $program->duration . ' years') : 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm md:col-span-2">
                            <p class="text-sm text-gray-500">Website</p>
                            @if($program->url)
                                <a href="{{ $program->url }}" target="_blank" class="mt-2 inline-block font-semibold text-[#7F64CE] underline transition hover:text-[#5a43b5]">
                                    Open Program
                                </a>
                            @else
                                <p class="mt-2 font-semibold text-[#7F64CE]">N/A</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tuition Fees --}}
                <div class="mb-8">
                    <h5 class="mb-4 text-lg font-bold text-[#7F64CE]">Tuition Fees</h5>

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

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">{{ $localLabel }}</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $localFee ? $localFee . '/year' : 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">{{ $otherLabel }}</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ $otherFee ? $otherFee . '/year' : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- English Requirements --}}
                @php
                    $req = $program->requirement;
                @endphp

                <div>
                    <h5 class="mb-4 text-lg font-bold text-[#7F64CE]">English Requirements</h5>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">SAT</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ optional($req)->sat ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">IELTS</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ optional($req)->ielts ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">TOEFL</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ optional($req)->toefl ?: 'N/A' }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-[#E6E0F8] bg-white p-5 shadow-sm">
                            <p class="text-sm text-gray-500">Minimum GPA</p>
                            <p class="mt-2 font-semibold text-[#7F64CE]">
                                {{ optional($req)->minimum_gpa ?: 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </details>
    @empty
        <div class="rounded-[28px] border border-[#E6E0F8] bg-white p-10 text-center shadow-sm">
            <p class="font-semibold text-gray-500">No programs found.</p>
        </div>
    @endforelse
</div>

@if(method_exists($programs, 'total') && $programs->total() > 0)
    <div class="mt-10 flex w-full flex-col items-center justify-between gap-5 rounded-[24px] border border-[#E6E0F8] bg-white px-5 py-4 shadow-sm md:flex-row">
        <div class="text-sm font-semibold text-[#5B527D]">
            {{ $programs->firstItem() }}-{{ $programs->lastItem() }} of {{ $programs->total() }}
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($programs->onFirstPage())
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F6F4FE] text-[#9B8FC0] opacity-50">‹</span>
            @else
                <a href="#" class="program-pagination-link flex h-9 w-9 items-center justify-center rounded-full bg-[#F6F4FE] font-semibold text-[#7F64CE] transition hover:bg-[#7F64CE] hover:text-white" data-page="{{ $programs->currentPage() - 1 }}">‹</a>
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
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#7F64CE] font-semibold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="#" class="program-pagination-link flex h-9 w-9 items-center justify-center rounded-full bg-[#F6F4FE] font-semibold text-[#7F64CE] transition hover:bg-[#7F64CE] hover:text-white" data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($programs->hasMorePages())
                <a href="#" class="program-pagination-link flex h-9 w-9 items-center justify-center rounded-full bg-[#F6F4FE] font-semibold text-[#7F64CE] transition hover:bg-[#7F64CE] hover:text-white" data-page="{{ $programs->currentPage() + 1 }}">›</a>
            @else
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F6F4FE] text-[#9B8FC0] opacity-50">›</span>
            @endif
        </div>
    </div>
@endif