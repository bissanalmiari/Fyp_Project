@php
    $warningsA = $comparisonData['program_a']['warnings'] ?? [];
    $warningsB = $comparisonData['program_b']['warnings'] ?? [];
@endphp

@if($selectedProgramA || $selectedProgramB)
    <div class="mt-12">
        <div class="mb-6 h-[1px] w-full bg-[#7F64CE]/20"></div>
        <h3 class="mb-8 text-3xl font-bold text-[#7F64CE]">Selected Programs</h3>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <div class="space-y-3">
                {{-- Program A --}}
                @if($selectedProgramA)
                    <div class="rounded-2xl bg-[#C3BFFA] p-4 shadow-md">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-[#7F64CE]/70">
                            Program A
                        </p>

                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <a href="{{ $selectedProgramA->url ?: '#' }}" target="_blank"
                                   class="flex items-center gap-2 text-xl font-bold text-[#7F64CE] hover:underline">
                                    <span>{{ $selectedProgramA->name }}</span>
                                    <span class="inline-flex items-center justify-center">
                                        <img
                                            src="{{ asset('images/share_icon.png') }}"
                                            alt="Open program"
                                            class="h-4 w-4 object-contain"
                                        >
                                    </span>
                                </a>

                                <p class="mt-1 text-sm text-[#5B527D]">
                                    {{ $selectedProgramA->university->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Category</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ optional($selectedProgramA->category)->name ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Sub Category</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ optional($selectedProgramA->subcategory)->name ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Location</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->university->city ?? 'N/A' }}, {{ $selectedProgramA->university->country ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Level</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->level ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Study Mode</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->study_mode ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Course Intensity</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->course_intensity ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Duration</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->duration ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3 sm:col-span-2">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Languages</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramA->languages->pluck('name')->join(', ') ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3 sm:col-span-2">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Tuition</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramATuition ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-2 text-[11px] text-[#5B527D]">Requirements</p>

                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                <div>
                                    <p class="text-[11px] text-[#5B527D]">GPA</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramA->requirement)->minimum_gpa ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">SAT</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramA->requirement)->sat ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">IELTS</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramA->requirement)->ielts ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">TOEFL</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramA->requirement)->toefl ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($warningsA))
                    @foreach($warningsA as $warning)
                        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ $warning }}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="space-y-3">
                {{-- Program B --}}
                @if($selectedProgramB)
                    <div class="rounded-2xl bg-[#C3BFFA] p-4 shadow-md">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-[#7F64CE]/70">
                            Program B
                        </p>

                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <a href="{{ $selectedProgramB->url ?: '#' }}" target="_blank"
                                   class="flex items-center gap-2 text-xl font-bold text-[#7F64CE] hover:underline">
                                    <span>{{ $selectedProgramB->name }}</span>
                                    <span class="inline-flex items-center justify-center">
                                        <img
                                            src="{{ asset('images/share_icon.png') }}"
                                            alt="Open program"
                                            class="h-4 w-4 object-contain"
                                        >
                                    </span>
                                </a>

                                <p class="mt-1 text-sm text-[#5B527D]">
                                    {{ $selectedProgramB->university->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Category</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ optional($selectedProgramB->category)->name ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Sub Category</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ optional($selectedProgramB->subcategory)->name ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Location</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->university->city ?? 'N/A' }}, {{ $selectedProgramB->university->country ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Level</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->level ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Study Mode</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->study_mode ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Course Intensity</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->course_intensity ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Duration</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->duration ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3 sm:col-span-2">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Languages</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramB->languages->pluck('name')->join(', ') ?: 'N/A' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-[#F6F4FE] p-3 sm:col-span-2">
                                <p class="mb-1 text-[11px] text-[#5B527D]">Tuition</p>
                                <p class="text-sm font-semibold text-[#7F64CE]">
                                    {{ $selectedProgramBTuition ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl bg-[#F6F4FE] p-3">
                            <p class="mb-2 text-[11px] text-[#5B527D]">Requirements</p>

                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                <div>
                                    <p class="text-[11px] text-[#5B527D]">GPA</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramB->requirement)->minimum_gpa ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">SAT</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramB->requirement)->sat ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">IELTS</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramB->requirement)->ielts ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] text-[#5B527D]">TOEFL</p>
                                    <p class="text-sm font-semibold text-[#7F64CE]">
                                        {{ optional($selectedProgramB->requirement)->toefl ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($warningsB))
                    @foreach($warningsB as $warning)
                        <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ $warning }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif